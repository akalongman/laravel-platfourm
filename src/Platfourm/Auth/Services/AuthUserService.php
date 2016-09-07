<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Auth\Services;

use Illuminate\Contracts\Auth\Factory as AuthContract;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Longman\Platfourm\Auth\Exceptions\AuthException;
use Longman\Platfourm\Auth\Exceptions\ForbiddenException;
use Longman\Platfourm\Auth\Exceptions\UnauthorizedException;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;
use Longman\Platfourm\User\Models\Eloquent\User;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface as SessionContract;

class AuthUserService implements AuthUserServiceContract
{
    use ThrottlesLogins;

    private $user = null;
    private $guard = null;

    private $repository;
    private $session;
    private $auth;
    private $config;

    public function __construct(
        UserRepository $repository,
        SessionContract $session,
        AuthContract $auth,
        ConfigContract $config
    ) {
        $this->repository = $repository;
        $this->session    = $session;
        $this->auth       = $auth;
        $this->config     = $config;
        $this->setUser();
    }

    public function setGuard($guard)
    {
        $this->guard = $guard;
        $this->setUser();
        return $this;
    }

    public function setUser()
    {
        $this->user = $this->auth->guard($this->guard)->user();
        if ($this->user && property_exists($this->user, 'loginasData')) {
            $this->user->setLoginAsData($this->session->get('loginas.user.data'));
        }
    }

    public function login(Request $request, $remember = false, array $input = ['email', 'password'])
    {
        return $this->attempt($request, $remember, $input);
    }

    public function attempt(Request $request, $remember = false, array $input = ['email', 'password'])
    {
        // validation here

        if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $seconds = $this->secondsRemainingOnLockout($request);

            throw new AuthException('Too many login attempts. Please try again in ' . $seconds . ' seconds.');
        }

        $credentials = $request->only($input);

        if ($this->auth->guard($this->guard)->attempt($credentials, $remember)) {
            $this->clearLoginAttempts($request);
            $user = $this->auth->guard($this->guard)->user();
            return $user;
        }

        if (!$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        throw new AuthException('These credentials do not match our records.');
    }

    public function logout()
    {
        $this->session->remove('loginas.user');

        $this->auth->guard($this->guard)->logout();
    }

    public function loginUsername()
    {
        return 'email';
    }

    public function isConsole()
    {
        return App::runningInConsole();
    }

    /**
     * @return \Longman\Platfourm\User\Models\Eloquent\User
     */
    public function getUser()
    {
        if (empty($this->user)) {
            throw new UnauthorizedException;
        }
        return $this->user;
    }

    /**
     * @return \Longman\Platfourm\User\Models\Eloquent\User
     */
    public function user()
    {
        if (empty($this->user)) {
            throw new UnauthorizedException;
        }
        return $this->user;
    }

    public function check()
    {
        if ($this->auth->guard($this->guard)->check()) {
            return true;
        }

        return false;
    }

    public function guest()
    {

        return !$this->check();
    }

    /**
     * @return mixed
     * @throws \Longman\Platfourm\Auth\Exceptions\UnauthorizedException
     */
    public function guard()
    {
        if (empty($this->auth->guard($this->guard))) {
            throw new UnauthorizedException;
        }
        return $this->auth->guard($this->guard);
    }

    public function hasRole($role)
    {
        return $this->getUser()->hasRole($role);
    }

    public function should($perm)
    {
        if (!$this->getUser()->can($perm)) {
            throw new ForbiddenException('Do not have permission to ' . str_replace('.', ' ', $perm));
        }
    }

    public function can($perm)
    {
        return $this->getUser()->can($perm);
    }

    public function canLoginAs(User $user)
    {
        if (!$this->can('user.loginas')) {
            return false;
        }

        if ($this->session->has('loginas.user')) {
            return false;
        }

        if ($this->getUser()->id == $user->id) {
            return false;
        }

        return true;
    }

    public function canDeleteUser(User $user)
    {
        if (!$this->can('user.delete')) {
            return false;
        }

        if ($this->getUser()->id == $user->id) {
            return false;
        }

        if ($this->session->has('loginas.user')) {
            return false;
        }

        return true;
    }

    public function canUpdateUser(User $user, $status = 1)
    {
        if (!$this->can('user.update')) {
            return false;
        }

        if ($this->getUser()->id == $user->id) {
            return false;
        }

        if ($this->session->has('loginas.user')) {
            return false;
        }

        return true;
    }

    public function loginAs($id)
    {
        $user = $this->repository->find($id);
        if (!$this->canLoginAs($user)) {
            throw new ForbiddenException('Do not have permission to login as user: ' . $id);
        }

        $this->session->put('loginas.user.id', $this->user()->id);
        $this->session->put('loginas.user.name', $this->user()->getFullname());
        $this->session->put('loginas.user.data', $this->user()->toArray());

        $user->setLoginAsData($this->session->get('loginas.user.data'));

        $this->auth->login($user);

        $this->user = $user;

        return $user;
    }

    public function logoutAs()
    {

        $id = $this->session->get('loginas.user.id');

        if (!$id) {
            throw new \RuntimeException('You are not switched to other user!');
        }

        $user = $this->repository->find($id);

        $this->session->forget('loginas.user');

        $this->auth->login($user);

        $this->user = $user;
        return $user;
    }

    public function __get($key)
    {
        if (isset($this->user->$key)) {
            return $this->user->$key;
        }
        throw new InvalidArgumentException('Attribute ' . $key . ' not found in ' . __CLASS__);
    }
}

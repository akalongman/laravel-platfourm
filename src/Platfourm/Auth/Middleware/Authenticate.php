<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Auth\Middleware;

use Closure;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;

class Authenticate
{

    protected $authService;

    public function __construct(AuthUserServiceContract $authService)
    {
        $this->authService = $authService;
    }

    public function handle($request, Closure $next)
    {

        if ($this->authService->guest()) {
            if ($request->wantsJson()) {
                $headers = [
                    'WWW-Authenticate' => 'Auth',
                ];
                return response('Unauthorized.', 401, $headers);
            } else {
                return $this->redirect();
            }
        }

        return $next($request);
    }

    protected function redirect()
    {
        $url = app()->isAdmin() ? admin_url('login') : site_url('login');
        return redirect()->guest($url)->with(['error' => 'You must log in first']);
    }

}

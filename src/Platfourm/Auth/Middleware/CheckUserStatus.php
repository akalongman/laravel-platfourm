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

class CheckUserStatus
{

    /**
     * @var \Longman\Platfourm\Contracts\Auth\AuthUserService
     */
    protected $authService;

    /**
     * CheckUserStatus constructor.
     *
     * @param \Longman\Platfourm\Contracts\Auth\AuthUserService $authService
     */
    public function __construct(AuthUserServiceContract $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param          $request
     * @param \Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if ($this->authService->check() && !$this->authService->user()->canLogin()) {
            $this->authService->logout();

            return redirect('auth/login')
                ->with(['error' => 'Your account is disabled']);
        }

        return $next($request);
    }
}

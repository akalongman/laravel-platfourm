<?php

namespace Longman\Platfourm\Auth\Middleware;

use Closure;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;

class CheckUserStatus
{

    protected $authService;

    public function __construct(AuthUserServiceContract $authService)
    {
        $this->authService = $authService;
    }

    public function handle($request, Closure $next)
    {
        if ($this->authService->check() && !$this->authService->user()->canLogin()) {
            die('Your account is disabled');
            $this->authService->logout();

            return redirect('auth/login')
                ->with(['error' => 'Your account is disabled']);
        }

        return $next($request);
    }
}

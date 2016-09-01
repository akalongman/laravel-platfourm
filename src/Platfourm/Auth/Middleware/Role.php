<?php

namespace Longman\Platfourm\Auth\Middleware;

use Closure;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;

class Role
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $authService;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @return void
     */
    public function __construct(AuthUserServiceContract $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure                  $next
     * @param                           $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {

        if ($this->authService->guest() || !$request->user()->hasRole(explode('|', $roles))) {
            if ($request->wantsJson()) {
                return response('Forbidden.', 403);
            } else {
                abort(403);
            }
        }

        return $next($request);
    }
}

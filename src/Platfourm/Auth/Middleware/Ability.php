<?php

namespace Longman\Platfourm\Auth\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;

class Ability
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
     * @param  \Longman\Platfourm\Contracts\Auth\AuthUserService $authService
     */
    public function __construct(AuthUserServiceContract $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param                          $roles
     * @param                          $permissions
     * @param bool                     $validateAll
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions, $validateAll = false)
    {
        if ($this->authService->guest() || !$request->user()->ability(
            explode('|', $roles),
            explode('|', $permissions),
            array('validate_all' => $validateAll)
        )
        ) {
            if ($request->wantsJson()) {
                return response('Forbidden.', 403);
            } else {
                abort(403);
            }
        }

        return $next($request);
    }
}

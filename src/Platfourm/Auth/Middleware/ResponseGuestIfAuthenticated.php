<?php

namespace Longman\Platfourm\Auth\Middleware;

use Closure;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;

class ResponseGuestIfAuthenticated
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
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->authService->check()) {
            if ($request->wantsJson()) {
                return response('Unauthorized.', 401);
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

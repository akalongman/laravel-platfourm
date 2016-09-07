<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;
use View;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected $app;

    protected $scopeNamespace;
    protected $httpNamespace;
    protected $viewNamespace;

    public function __construct()
    {
        $this->app = app();
        $lang      = $this->app->getLocale();
        View::share([
                        'lang' => $lang,
                    ]);
    }

    /**
     * Get auth user service.
     *
     * @param  string $guard
     * @return \Longman\Platfourm\Auth\Services\AuthUserService
     */
    public function getAuthService($guard = null)
    {
        $authService = $this->app->make(AuthUserServiceContract::class);
        return $authService->setGuard($guard);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  array  $data
     * @param  array  $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view($view, $data = [], $mergeData = [])
    {
        $viewMask = [];
        if ($this->scopeNamespace) {
            $viewMask[] = $this->scopeNamespace;
        }
        if ($this->viewNamespace) {
            $viewMask[] = $this->viewNamespace;
        }
        $viewMask[] = $view;

        return view(implode('.', $viewMask), $data, $mergeData);
    }

    /**
     * Get an instance of the redirector.
     *
     * @param  string|null $to
     * @param  int         $status
     * @param  array       $headers
     * @param  bool        $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    protected function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if ($to === null) {
            return redirect();
        }

        $toMask = [];
        if ($this->scopeNamespace && $this->scopeNamespace !== 'site') {
            $toMask[] = $this->scopeNamespace;
        }
        if ($this->httpNamespace) {
            $toMask[] = $this->httpNamespace;
        }
        $toMask[] = $to;

        return redirect(implode('/', $toMask), $status, $headers, $secure);
    }

    /**
     * Return a new response from the application.
     *
     * @param  string $content
     * @param  int    $status
     * @param  array  $headers
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    protected function response($content = '', $status = 200, array $headers = [])
    {
        return response($content, $status, $headers);
    }
}

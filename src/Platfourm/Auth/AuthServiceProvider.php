<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Longman\Platfourm\Auth\RemoteUserProvider;
use Longman\Platfourm\Auth\Repositories\RemoteUserRepository;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(AuthManager $auth)
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \Longman\Platfourm\Contracts\Auth\AuthUserService::class,
            \Longman\Platfourm\Auth\Services\AuthUserService::class
        );
    }
}

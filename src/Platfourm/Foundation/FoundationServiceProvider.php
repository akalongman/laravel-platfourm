<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Foundation;

use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Longman\Platfourm\Foundation\Console\ClearCommand;
use Longman\Platfourm\Foundation\Console\CompileCommand;
use Longman\Platfourm\Foundation\Events\ApplicationScopeMatched;

class FoundationServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Clear'   => 'command.clear',
        'Compile' => 'command.compile',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [

    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands($this->commands);

        $this->registerCommands($this->devCommands);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->detectScope();
    }

    protected function detectScope()
    {
        // set global scope if no routing involved
        $this->app['config']->set('app.scope', 'global');

        // set application scope
        $this->app['events']->listen(RouteMatched::class, function ($match) {
            $routeName    = $match->route->getName();
            $admin_prefix = $this->app['config']->get('cms.admin_prefix', 'admin');
            $isAdmin      = strpos($routeName, $admin_prefix . '.') !== false;
            $scope        = $isAdmin ? 'admin' : 'site';
            $this->app['config']->set('app.scope', $scope);
            $this->app['events']->fire(new ApplicationScopeMatched($scope));
        });
    }

    /**
     * Register the given commands.
     *
     * @param  array $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerClearCommand()
    {
        $this->app->singleton('command.clear', function ($app) {
            return new ClearCommand();
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCompileCommand()
    {
        $this->app->singleton('command.compile', function ($app) {
            return new CompileCommand();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        if ($this->app->environment('production')) {
            return array_values($this->commands);
        } else {
            return array_merge(array_values($this->commands), array_values($this->devCommands));
        }
    }

}

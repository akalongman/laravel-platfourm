<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Repository\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Longman\Platfourm\Repository\Events\RepositoryEntityCreated' => [
            'Longman\Platfourm\Repository\Listeners\CleanCacheRepository',
        ],
        'Longman\Platfourm\Repository\Events\RepositoryEntityUpdated' => [
            'Longman\Platfourm\Repository\Listeners\CleanCacheRepository',
        ],
        'Longman\Platfourm\Repository\Events\RepositoryEntityDeleted' => [
            'Longman\Platfourm\Repository\Listeners\CleanCacheRepository',
        ],
    ];
}

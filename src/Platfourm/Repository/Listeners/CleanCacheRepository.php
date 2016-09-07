<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Repository\Listeners;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Longman\Platfourm\Contracts\Repository\Repository;
use Longman\Platfourm\Repository\Events\RepositoryEventBase;
use Longman\Platfourm\Repository\Helpers\CacheKeys;

/**
 * Class CleanCacheRepository.
 */
class CleanCacheRepository
{
    /**
     * @var CacheRepository
     */
    protected $cache = null;

    /**
     * @var Repository
     */
    protected $repository = null;

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * @var string
     */
    protected $action = null;

    public function __construct()
    {
        $this->cache = app(config('database.cache.repository', 'cache'));
    }

    /**
     * @param RepositoryEventBase $event
     */
    public function handle(RepositoryEventBase $event)
    {
        try {
            $cleanEnabled = config('repository.cache.clean.enabled', true);

            if ($cleanEnabled) {
                $this->repository = $event->getRepository();
                $this->model      = $event->getModel();
                $this->action     = $event->getAction();

                if (config("repository.cache.clean.on.{$this->action}", true)) {
                    $cacheKeys = CacheKeys::getKeys(get_class($this->repository));

                    if (is_array($cacheKeys)) {
                        foreach ($cacheKeys as $key) {
                            $this->cache->forget($key);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }

}

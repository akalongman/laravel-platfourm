<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Contracts\Repository;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

/**
 *  Cacheable.
 */
interface Cacheable
{
    /**
     * Set Cache Repository.
     *
     * @param CacheRepository $repository
     *
     * @return $this
     */
    public function setCacheRepository(CacheRepository $repository);

    /**
     * Return instance of Cache Repository.
     *
     * @return CacheRepository
     */
    public function getCacheRepository();

    /**
     * Get Cache key for the method.
     *
     * @param $method
     * @param $args
     *
     * @return string
     */
    public function getCacheKey($method, $args = null);

    /**
     * Get cache minutes.
     *
     * @return int
     */
    public function getCacheMinutes();

    /**
     * Skip Cache.
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCache($status = true);
}

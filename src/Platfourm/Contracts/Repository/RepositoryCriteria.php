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

use Illuminate\Support\Collection;

/**
 *  RepositoryCriteria.
 */
interface RepositoryCriteria
{
    /**
     * Push Criteria for filter the query.
     *
     * @param Criteria $criteria
     *
     * @return $this
     */
    public function pushCriteria(Criteria $criteria);

    /**
     * Get Collection of Criteria.
     *
     * @return Collection
     */
    public function getCriteria();

    /**
     * Find data by Criteria.
     *
     * @param Criteria $criteria
     *
     * @return mixed
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * Skip Criteria.
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true);
}

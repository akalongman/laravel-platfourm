<?php

namespace Longman\Platfourm\Contracts\Repository;

/**
 *  Criteria.
 */
interface Criteria
{
    /**
     * Apply criteria in query repository.
     *
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository);
}

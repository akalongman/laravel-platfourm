<?php

namespace App\Repositories\Identity;

use App\Contracts\Repositories\Repository as RepositoryContract;
use App\Repositories\Criteria;

class IdCriteria extends Criteria
{

    /**
     * @param                     $model
     * @param  RepositoryContract $repository
     * @return mixed
     */
    public function apply($model, RepositoryContract $repository)
    {
        $model = $model->where('id', '>', 50);
        return $model;
    }
}

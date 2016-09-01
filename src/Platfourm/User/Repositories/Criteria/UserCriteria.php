<?php

namespace Longman\Platfourm\User\Repositories\Criteria;

use Longman\Platfourm\Contracts\Repository\Criteria;
use Longman\Platfourm\Contracts\Repository\Repository;

class UserCriteria implements Criteria
{
    public function apply($model, Repository $repository)
    {
        $model = $model->where('user_id', '=', user_id());

        return $model;
    }
}

<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

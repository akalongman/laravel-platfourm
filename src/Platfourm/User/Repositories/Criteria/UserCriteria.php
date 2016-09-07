<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Auth\Repositories\Eloquent;

use Longman\Platfourm\Auth\Models\Eloquent\AccessLog;
use Longman\Platfourm\Contracts\Repository\Repository;
use Longman\Platfourm\Contracts\Repository\RepositoryCriteria;
use Longman\Platfourm\Repository\Eloquent\BaseRepository;

class AccessLogRepository extends BaseRepository implements Repository, RepositoryCriteria
{

    public function model()
    {
        return AccessLog::class;
    }

}

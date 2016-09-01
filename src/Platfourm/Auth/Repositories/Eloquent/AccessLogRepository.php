<?php

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

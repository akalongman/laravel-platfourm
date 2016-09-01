<?php

namespace Longman\Platfourm\User\Repositories\Eloquent;

use Longman\Platfourm\Contracts\Repository\Repository;
use Longman\Platfourm\Contracts\Repository\RepositoryCriteria;
use Longman\Platfourm\Repository\Eloquent\BaseRepository;
use Longman\Platfourm\User\Models\Eloquent\Permission;

class PermissionRepository extends BaseRepository implements Repository, RepositoryCriteria
{

    public function model()
    {
        return Permission::class;
    }

}

<?php

namespace Longman\Platfourm\User\Repositories\Eloquent;

use Longman\Platfourm\Contracts\Repository\Repository;
use Longman\Platfourm\Contracts\Repository\RepositoryCriteria;
use Longman\Platfourm\Repository\Eloquent\BaseRepository;
use Longman\Platfourm\User\Models\Eloquent\Role;

class RoleRepository extends BaseRepository implements Repository, RepositoryCriteria
{

    public function model()
    {
        if (class_exists(\App\Models\Role::class)) {
            return \App\Models\Role::class;
        }
        return Role::class;
    }

}

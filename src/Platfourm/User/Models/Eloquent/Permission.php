<?php

namespace Longman\Platfourm\User\Models\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use SoftDeletes;

    protected $fillable = ['name', 'display_name', 'description'];

    protected $dates = ['deleted_at'];
}

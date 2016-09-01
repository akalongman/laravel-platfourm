<?php

namespace Longman\Platfourm\User\Models\Eloquent\Traits;

use Config;

trait PermissionTrait
{
    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Config::get('entrust.role'), Config::get('entrust.permission_role_table'));
    }

    //change function boot to
    public static function bootEntrustPermissionTrait()
    {
        static::deleting(function ($permission) {
            if (!method_exists(static::class, 'bootSoftDeletes')) {
                $permission->roles()->sync([]);
            }

            return true;
        });
    }
}

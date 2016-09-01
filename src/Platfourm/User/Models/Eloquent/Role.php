<?php

namespace Longman\Platfourm\User\Models\Eloquent;

use Cache;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Longman\Platfourm\Database\Eloquent\Model;
use Longman\Platfourm\User\Models\Eloquent\EntrustRoleTrait;
use Zizaco\Entrust\EntrustRole;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'display_name', 'description'];

    protected $searchableFields = ['name', 'display_name', 'description'];

    protected $sortableFields = ['name', 'display_name', 'description', 'created_at', 'updated_at'];

    protected $dates = ['deleted_at'];

    public function cachedPermissions()
    {
        $cacheKey = 'entrust_permissions_for_role_' . $this->getKey();
        return Cache::remember($cacheKey, Config::get('cache.ttl'), function () {
            return $this->permissions()->get();
        });
    }

    /**
     * Checks if the role has a permission by its name.
     *
     * @param  string|array $name       Permission name or array of permission names.
     * @param  bool         $requireAll All permissions in the array are required.
     * @return bool
     */
    public function hasPermission($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);

                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the permissions were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the permissions were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedPermissions() as $permission) {
                if ($permission->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    public function users()
    {
        $model = User::class;
        if (class_exists(\App\Models\User::class)) {
            $model = \App\Models\User::class;
        }
        return $this->hasMany($model);
    }

    public function permissions()
    {
        $model = Permission::class;
        if (class_exists(\App\Models\Permission::class)) {
            $model = \App\Models\Permission::class;
        }
        return $this->belongsToMany($model);
    }

}

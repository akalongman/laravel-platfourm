<?php

namespace Longman\Platfourm\User\Models\Eloquent\Traits;

use Cache;
use Config;

trait RoleTrait
{

    public function cachedPermissions()
    {
        $cacheKey = 'entrust_permissions_for_role_' . $this->getKey();
        return Cache::/*tags(Config::get('entrust.permission_role_table'))->*/
        remember($cacheKey, Config::get('cache.ttl'), function () {
            return $this->perms()->get();
        });
    }

    public static function bootEntrustRoleTrait()
    {
        static::saved(function ($item) {
            //Cache::tags(Config::get('entrust.permission_role_table'))->flush();
            Cache::forget('entrust_permissions_for_role_' . $item->getKey());
        });
        static::deleted(function ($item) {
            //Cache::tags(Config::get('entrust.permission_role_table'))->flush();
            Cache::forget('entrust_permissions_for_role_' . $item->getKey());
        });
        if (method_exists(static::class, 'restored')) {
            static::restored(function ($item) {
                //Cache::tags(Config::get('entrust.permission_role_table'))->flush();
                Cache::forget('entrust_permissions_for_role_' . $item->getKey());
            });
        }
    }

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->hasMany(Config::get('auth.model'));
        // return $this->belongsToMany(Config::get('auth.model'), Config::get('entrust.role_user_table'));
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(Config::get('entrust.permission'), Config::get('entrust.permission_role_table'));
    }

    /**
     * Boot the role model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($role) {
            if (!method_exists(Config::get('entrust.role'), 'bootSoftDeletes')) {
                $role->users()->sync([]);
                $role->perms()->sync([]);
            }

            return true;
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

    /**
     * Save the inputted permissions.
     *
     * @param  mixed $inputPermissions
     * @return void
     */
    public function savePermissions($inputPermissions)
    {
        if (!empty($inputPermissions)) {
            $this->perms()->sync($inputPermissions);
        } else {
            $this->perms()->detach();
        }
    }

    /**
     * Attach permission to current role.
     *
     * @param  object|array $permission
     * @return void
     */
    public function attachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->perms()->attach($permission);
    }

    /**
     * Detach permission from current role.
     *
     * @param  object|array $permission
     * @return void
     */
    public function detachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            $permission = $permission['id'];
        }

        $this->perms()->detach($permission);
    }

    /**
     * Attach multiple permissions to current role.
     *
     * @param  mixed $permissions
     * @return void
     */
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * Detach multiple permissions from current role
     *
     * @param  mixed $permissions
     * @return void
     */
    public function detachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }
}

<?php

namespace Longman\Platfourm\User\Models\Eloquent;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Longman\Platfourm\Auth\Traits\LoginAsPossibility;
use Longman\Platfourm\Database\Eloquent\ActionLog\ActionLogTrait;
use Longman\Platfourm\Database\Eloquent\EntityLock\EntityLockTrait;
use Longman\Platfourm\Database\Eloquent\Model;
use Longman\Platfourm\Database\Eloquent\Traits\SoftDeletes;
use Longman\Platfourm\Database\Eloquent\Traits\UuidForPrimary;
use Longman\Platfourm\User\Models\Eloquent\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes, UuidForPrimary,
        ActionLogTrait, EntityLockTrait, LoginAsPossibility;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'avatar',
        'lastname',
        'role_id',
        'is_developer',
        'country_id',
        'mobile_number',
        'gender',
        'birth_date',
        'address',
        'activate_token',
        'status',
    ];

    protected $dates = ['deleted_at', 'birth_date'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $searchableFields = ['email', 'firstname', 'lastname'];

    protected $filterableFields = ['status' => '='];

    protected $sortableFields = ['email', 'firstname', 'lastname', 'status', 'created_at', 'updated_at'];

    public function isDeveloper()
    {
        return (bool)$this->getAttribute('is_developer');
    }

    public function getAvatar()
    {
        $avatar = $this->getAttribute('avatar');
        if (!empty($avatar)) {
            $path = config('cms.user.avatar.path', 'cache/avatar');
            return '/' . $path . '/' . $avatar;
        }

        $default_path = config('cms.user.avatar.default');
        if (empty($default_path)) {
            return null;
        }

        return '/' . $default_path;
    }

    public function getFullname()
    {
        return $this->getAttribute('firstname') . ' ' . $this->getAttribute('lastname');
    }

    public function canLogin()
    {
        return $this->getAttribute('status') == 1;
    }

    public function role()
    {
        $model = Role::class;
        if (class_exists(\App\Models\Role::class)) {
            $model = \App\Models\Role::class;
        }
        return $this->belongsTo($model);
    }

    public function permissions()
    {
        return $this->role->permissions;

        /*$roleModel = Role::class;
        if (class_exists(\App\Models\Role::class)) {
            $roleModel = \App\Models\Role::class;
        }

        $permissionModel = Permission::class;
        if (class_exists(\App\Models\Permission::class)) {
            $permissionModel = \App\Models\Permission::class;
        }

        return $this->hasManyThrough($roleModel, $permissionModel);*/
    }

    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        } else {
            return $this->role->name == $name;
        }

        return false;
    }

    public function can($permission, $requireAll = false)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);

                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            // Validate against the Permission table
            foreach ($this->role->cachedPermissions() as $perm) {
                if (str_is($permission, $perm->name)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function toArray()
    {
        $this->permissions = $this->role->permissions()->get(['id', 'name'])->toArray();

        $array = parent::toArray();

        if (property_exists($this, 'loginasData')) {
            $array['loginasData'] = $this->getLoginAsData();
        }

        $array['avatar'] = $this->getAvatar();

        return $array;
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country');
    }

    public function accessLog()
    {
        return $this->hasMany('App\Models\AccessLog');
    }

}

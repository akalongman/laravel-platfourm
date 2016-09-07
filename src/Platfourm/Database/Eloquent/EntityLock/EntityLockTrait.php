<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Database\Eloquent\EntityLock;

use Auth;
use Carbon\Carbon;
use Longman\Platfourm\Database\Eloquent\EntityLock\EntityLock;

trait EntityLockTrait
{

    protected $entityLock = true;

    /**
     * Boot the trait for the model.
     *
     * @return void
     */
/*    public static function bootActionLogTrait()
    {

        static::created(function ($model) {
            if (!empty($model->actionLogging)) {
                static::storeActionLog($model, 'create');
            }
        });

        static::updated(function ($model) {
            if (!empty($model->actionLogging)) {
                static::storeActionLog($model, 'update');
            }
        });

        static::deleted(function ($model) {
            if (!empty($model->actionLogging)) {
                static::storeActionLog($model, 'delete');
            }
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                if (!empty($model->actionLogging)) {
                    static::storeActionLog($model, 'restore');
                }
            });
        }
    }*/

    public function lockIn()
    {
        $entityLock = static::getEntityLockModelInstance();

        $model = $this;

        $user_id = Auth::user() ? Auth::user()->id : '';

        $lang = app()->getLocale();

        $className = get_class($model);

        $data = [
            'user_id' => $user_id,
            'lang' => $lang,
            'entity' => static::getModelClassName($model),
            'scope' => app()->getScope(),
            'action' => $action,
            'before_data' => $before_data,
            'after_data' => $after_data,
            'request_data' => app('request')->all(),
            'url' => app('request')->fullUrl(),
            'referer' => app('request')->server->get('HTTP_REFERER'),
            'ip_address' => app('request')->ip(),
            'user_agent' => app('request')->server->get('HTTP_USER_AGENT'),
        ];

        $entityLock->create($data);
    }

    protected static function getEntityLockModelInstance()
    {
        $model = EntityLock::class;
        if (class_exists(\App\Models\EntityLock::class)) {
            $model = \App\Models\EntityLock::class;
        }
        return new $model;
    }


    public function withoutEntityLock()
    {
        $this->entityLock = false;
        return $this;
    }

    public function isLocked()
    {
        $date = Carbon::now()->subMinutes(5);
        return $this->entityLock()->where('locked_at', '>=', $date->toDateTimeString());
    }

    /**
     * Polymorphic relationship. Second parameter to morphOne/morphMany
     * should be the same as the prefix for the *_id/*_type fields.
     */
    public function entityLock()
    {
        $model = EntityLock::class;
        if (class_exists(\App\Models\EntityLock::class)) {
            $model = \App\Models\EntityLock::class;
        }

        return $this->morphOne($model, 'entity');
    }

}

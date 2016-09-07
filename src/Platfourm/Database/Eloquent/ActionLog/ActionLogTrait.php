<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Database\Eloquent\ActionLog;

use Auth;

trait ActionLogTrait
{

    public $actionLogging = true;

    /**
     * Boot the trait for the model.
     *
     * @return void
     */
    public static function bootActionLogTrait()
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
    }

    public function withoutActionLogging()
    {
        $this->actionLogging = false;
        return $this;
    }

    protected static function storeActionLog($model, $action = 'read')
    {
        $actionLog = static::getActionLogModelInstance();
        $user_id   = Auth::user() ? Auth::user()->id : '';

        $before_data = $model->original;
        $after_data  = $model->attributes;

        $lang = app()->getLocale();

        $className = get_class($model);

        $data = [
            'user_id'      => $user_id,
            'lang'         => $lang,
            'entity'       => $className,
            'scope'        => app()->getScope(),
            'action'       => $action,
            'before_data'  => $before_data,
            'after_data'   => $after_data,
            'request_data' => app('request')->all(),
            'url'          => app('request')->fullUrl(),
            'referer'      => app('request')->server->get('HTTP_REFERER'),
            'ip_address'   => app('request')->ip(),
            'user_agent'   => app('request')->server->get('HTTP_USER_AGENT'),
        ];

        $actionLog->create($data);
    }

    protected static function getActionLogModelInstance()
    {
        $model = ActionLog::class;
        if (class_exists(\App\Models\ActionLog::class)) {
            $model = \App\Models\ActionLog::class;
        }
        return new $model;
    }

}

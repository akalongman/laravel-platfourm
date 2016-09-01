<?php

namespace Longman\Platfourm\Database\Eloquent\ActionLog;

use Longman\Platfourm\Database\Eloquent\Model;
use Longman\Platfourm\User\Models\Eloquent\User;

class ActionLog extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'action_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'lang', 'entity', 'scope', 'action', 'url', 'referer', 'before_data',
        'after_data', 'request_data', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'request_data' => 'array',
    ];


    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function user()
    {
        $model = User::class;
        if (class_exists(\App\Models\User::class)) {
            $model = \App\Models\User::class;
        }
        return $this->hasOne($model);
    }

}

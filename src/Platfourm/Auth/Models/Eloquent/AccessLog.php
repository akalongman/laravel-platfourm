<?php

namespace Longman\Platfourm\Auth\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_access_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'scope', 'ip_address', 'user_agent', 'loginas_data'];

    protected $casts = [
        'loginas_data' => 'array',
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

}

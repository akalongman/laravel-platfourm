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

use Longman\Platfourm\Database\Eloquent\Model;
use Longman\Platfourm\User\Models\Eloquent\User;

class EntityLock extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'entity_locks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_type', 'entity_id', 'locked_at', 'locked_by'
    ];

    protected $dates = ['locked_at'];


    public static function boot()
    {
        static::creating(function ($model) {
            $model->locked_at = $model->freshTimestamp();
        });
    }

    /**
     * Polymorphic relationship. Name of the relationship should be
     * the same as the prefix for the *_id/*_type fields.
     */
    public function entity()
    {
        return $this->morphTo();
    }


    public function user()
    {
        $model = User::class;
        if (class_exists(\App\Models\User::class)) {
            $model = \App\Models\User::class;
        }
        return $this->belongsTo($model, 'locked_by');
    }

}

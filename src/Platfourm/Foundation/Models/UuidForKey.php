<?php

namespace Longman\Platfourm\Foundation\Models;

use Ramsey\Uuid\Uuid;

trait UuidForKey
{

    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function bootUuidForKey()
    {
        static::creating(function ($model) {
            $key = $model->getKeyName();
            if (empty($model->attributes[$key])) {
                $model->incrementing = false;
                $model->{$key}       = Uuid::uuid1()->toString();
            }
        });
    }

}

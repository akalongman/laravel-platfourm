<?php

namespace Longman\Platfourm\Database\Eloquent\Traits;

use Ramsey\Uuid\Uuid;

trait UuidForPrimary
{

    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function bootUuidForPrimary()
    {
        static::creating(function ($model) {
            $key = $model->getKeyName();
            if (empty($model->attributes[$key])) {
                $model->incrementing = false;
                $model->{$key}       = Uuid::uuid4()->toString();
            }
        });
    }

}

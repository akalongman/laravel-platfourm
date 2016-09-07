<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

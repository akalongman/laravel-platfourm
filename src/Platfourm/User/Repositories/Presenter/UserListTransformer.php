<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Litepie\User\Repositories\Presenter;

use League\Fractal\TransformerAbstract;

class UserListTransformer extends TransformerAbstract
{
    public function transform(\App\User $user)
    {
        return [
            'id'             => $user->getRouteKey(),
            'reporting_to'   => $user->reporting_to,
            'name'           => $user->name,
            'email'          => $user->email,
            'password'       => $user->password,
            'active'         => $user->active,
            'remember_token' => $user->remember_token,
            'sex'            => $user->sex,
            'dob'            => $user->dob,
            'designation'    => $user->designation,
            'mobile'         => $user->mobile,
            'phone'          => $user->phone,
            'address'        => $user->address,
            'street'         => $user->street,
            'city'           => $user->city,
            'district'       => $user->district,
            'state'          => $user->state,
            'country'        => $user->country,
            'photo'          => $user->photo,
            'web'            => $user->web,
            'social_login'   => $user->social_login,
        ];
    }
}

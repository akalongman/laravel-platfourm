<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Contracts\Auth;

interface AuthUserService
{

    /**
     * @return \Longman\Platfourm\User\Models\Eloquent\User
     */
    public function getUser();

    /**
     * @return \Longman\Platfourm\User\Models\Eloquent\User
     */
    public function user();

    /**
     * @return mixed
     * @throws \Longman\Platfourm\Auth\Exceptions\UnauthorizedException
     */
    public function guard();

    /**
     * @return bool
     */
    public function check();

    public function hasRole($role);

    public function can($perm);

    public function isConsole();

    /**
     * @param $perm
     * @throws \Longman\Platfourm\Auth\Exceptions\ForbiddenException
     */
    public function should($perm);

}

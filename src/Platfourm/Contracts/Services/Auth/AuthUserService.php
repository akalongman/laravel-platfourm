<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Contracts\Services\Auth;

interface AuthUserService
{

    public function getUser();

    public function check();

    public function hasRole($role);

    public function can($perm);

    public function isConsole();

    public function should($perm);

}

<?php

namespace Longman\Platfourm\Contracts\Auth;

interface AuthUserService
{

    public function getUser();

    public function check();

    public function hasRole($role);

    public function can($perm);

    public function isConsole();

    public function should($perm);

}

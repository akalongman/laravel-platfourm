<?php

namespace Longman\Platfourm\Http\Controllers;

abstract class AdminController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        // Set scope namespace
        $admin_prefix         = config('cms.admin_prefix', 'admin');
        $this->scopeNamespace = $admin_prefix;
    }

}

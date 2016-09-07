<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

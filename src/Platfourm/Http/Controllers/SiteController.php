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

abstract class SiteController extends Controller
{
    protected $scopeNamespace = 'site';

    public function __construct()
    {
        parent::__construct();
    }

}

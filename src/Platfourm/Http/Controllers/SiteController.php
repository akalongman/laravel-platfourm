<?php

namespace Longman\Platfourm\Http\Controllers;

abstract class SiteController extends Controller
{
    protected $scopeNamespace = 'site';

    public function __construct()
    {
        parent::__construct();
    }

}

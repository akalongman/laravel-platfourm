<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Foundation\Events;

/**
 * Class ApplicationScopeMatched
 *
 * @package Longman\Platfourm\Foundation\Events
 */
class ApplicationScopeMatched
{
    /**
     * @var string
     */
    public $scope;

    /**
     * ApplicationScopeMatched constructor.
     *
     * @param $scope
     */
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

}

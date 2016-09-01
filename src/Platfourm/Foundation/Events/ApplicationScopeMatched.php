<?php

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

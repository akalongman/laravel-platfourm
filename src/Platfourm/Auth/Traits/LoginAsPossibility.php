<?php

namespace Longman\Platfourm\Auth\Traits;

trait LoginAsPossibility
{

    public $loginasData = null;

    public function setLoginAsData($data)
    {
        $this->loginasData = $data;
        return $this;
    }

    public function getLoginAsData()
    {
        return $this->loginasData;
    }

}

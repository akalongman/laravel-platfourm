<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

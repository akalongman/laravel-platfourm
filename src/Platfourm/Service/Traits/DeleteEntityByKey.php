<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Service\Traits;

trait DeleteEntityByKey
{

    public function run($id)
    {
        $this->checkRepository();

        $entity = $this->repository->get($id);

        $status = $this->repository->delete($entity);
        return $this->parseResult($status);
    }
}

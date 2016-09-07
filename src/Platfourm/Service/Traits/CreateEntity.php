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

trait CreateEntity
{

    public function run(array $data)
    {
        $this->checkRepository();

        $entity = $this->repository->create($data);

        return $this->parseResult($entity);
    }
}

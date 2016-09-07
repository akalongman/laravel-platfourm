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

trait FindAllEntities
{

    public function run(array $columns = ['*'])
    {
        $this->checkRepository();

        $entityCollection = $this->repository->findAll($columns);

        /*if (empty($entityCollection)) {
            throw new ValueNotFoundException;
        }*/

        return $this->parseResult($entityCollection);
    }

}

<?php

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

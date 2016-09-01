<?php

namespace Longman\Platfourm\Service\Traits;

trait GetEntityByKey
{

    public function run($id, $columns = ['*'])
    {
        $this->checkRepository();

        $entity = $this->repository->find($id, $columns);

        return $this->parseResult($entity);
    }
}

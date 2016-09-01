<?php

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

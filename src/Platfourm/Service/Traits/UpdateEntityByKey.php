<?php

namespace Longman\Platfourm\Service\Traits;

trait UpdateEntityByKey
{

    public function run($id, array $data)
    {
        $this->checkRepository();

        $entity = $this->repository->update($id, $data);

        return $this->parseResult($entity);
    }
}

<?php

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

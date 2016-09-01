<?php

namespace Longman\Platfourm\Service\Traits;

use Exception;

trait RestoreEntityByKey
{

    public function run($id)
    {
        $this->checkRepository();

        $item = $this->repository->withTrashed()->find($id);

        if (!$item->trashed()) {
            throw new Exception('Entity is not trashed');
        }

        $item = $this->repository->restore($id);

        return $this->parseResult($item);
    }
}

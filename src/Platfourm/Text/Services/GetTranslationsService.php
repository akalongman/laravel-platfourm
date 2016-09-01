<?php

namespace Longman\Platfourm\Text\Services;

use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Service\Traits\FindEntities;
use Longman\Platfourm\Text\Repositories\Eloquent\TextRepository;

class GetTranslationsService extends EntityService
{
    use FindEntities;

    protected $repository;

    protected $authUserService;

    public function __construct(
        TextRepository $repository
    ) {
        $this->repository = $repository;
    }

    protected function parseResult($result)
    {
        $return = [];
        foreach ($result as $row) {
            $return[$row->key] = $row->value;
        }
        return $return;
    }

}

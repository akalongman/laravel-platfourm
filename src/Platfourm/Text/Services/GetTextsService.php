<?php

namespace Longman\Platfourm\Text\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Service\Traits\SearchAndPaginateEntities;
use Longman\Platfourm\Text\Repositories\Eloquent\TextRepository;

class GetTextsService extends EntityService
{
    use SearchAndPaginateEntities;

    protected $repository;

    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        TextRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        $authUserService->should('text.*');
    }

    protected function parseResult($result)
    {
        $collection = $result->getCollection()->toArray();

        $newCollection = [];
        foreach ($collection as $item) {
            $newCollection[$item['key']][$item['lang']] = $item;
        }

        $newCollection = collect($newCollection);
        $result->setCollection($newCollection);

        return $result;
    }

}

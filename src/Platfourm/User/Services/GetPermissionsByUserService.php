<?php

namespace Longman\Platfourm\Auth\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;

class GetPermissionsByUserService extends EntityService
{
    protected $repository;
    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        PermissionRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        //$authUserService->should('roles.*');
    }

    public function run($user_id, $columns = ['*'])
    {
        $this->checkRepository();

        $entity = $this->repository->find($id, $columns);
        return $entity;
    }

}

<?php

namespace Longman\Platfourm\User\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Service\Traits\SearchAndPaginateEntities;
use Longman\Platfourm\User\Repositories\Eloquent\RoleRepository;

class GetRolesService extends EntityService
{
    use SearchAndPaginateEntities;

    protected $repository;

    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        RoleRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        $authUserService->should('role.*');
    }
}

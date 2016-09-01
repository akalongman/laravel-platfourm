<?php

namespace Longman\Platfourm\User\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Service\Traits\GetEntityByKey;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;

class GetUserService extends EntityService
{
    use GetEntityByKey;

    protected $repository;

    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        UserRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        $authUserService->should('user.*');
    }
}

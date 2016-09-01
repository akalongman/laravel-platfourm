<?php

namespace Longman\Platfourm\User\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Service\Traits\UpdateEntityByKey;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;

class UpdateUserService extends EntityService
{
    use UpdateEntityByKey;

    protected $repository;
    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        UserRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        $authUserService->should('user.update');
    }
}

<?php

namespace Longman\Platfourm\User\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Foundation\Repositories\Repository;
use Longman\Platfourm\Service\CreateEntityService;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;

class CreateUserService extends CreateEntityService
{

    protected $repository;

    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        UserRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        $authUserService->should('user.create');
    }

    public function run(array $data)
    {
        $this->checkRepository();

        $data['password'] = bcrypt($data['password']);

        $entity = $this->repository->create($data);

        return $entity;
    }

}

<?php

namespace Longman\Platfourm\User\Services;

use Longman\Platfourm\Auth\Exceptions\ForbiddenException;
use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;

class DeleteUserService extends EntityService
{

    protected $repository;

    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        UserRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        $authUserService->should('user.delete');
    }

    public function run($id)
    {
        $this->checkRepository();

        $item = $this->repository->find($id);

        if (!$this->authUserService->canDeleteUser($item)) {
            throw new ForbiddenException('Do not have permission to update this user');
        }

        $item = $this->repository->delete($id);

        return $item;
    }
}

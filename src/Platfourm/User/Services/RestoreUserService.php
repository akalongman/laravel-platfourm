<?php

namespace Longman\Platfourm\User\Services;

use Exception;
use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;

class RestoreUserService extends EntityService
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

        $item = $this->repository->withTrashed()->find($id);

        if (!$item->trashed()) {
            throw new Exception('Entity is not trashed');
        }

        /*if (!$this->authUserService->canDeleteUser($item)) {
            throw new ForbiddenException('Do not have permission to update this user');
        }*/

        $item = $this->repository->restore($id);

        return $item;
    }
}

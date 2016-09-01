<?php

namespace App\Services\Auth;

use App\Contracts\Services\Auth\AuthUserService as AuthUserServiceContract;
use App\Exceptions\RepositoryNotFoundException;
use App\Repositories\Identity\UserRepository;
use App\Repositories\Repository;
use App\Services\EntityService;
use App\Services\ServiceDispatcher;

class UpdateProfileService extends EntityService
{
    protected $repository;
    protected $authUserService;

    public function __construct(
        AuthUserServiceContract $authUserService,
        UserRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;
    }

    public function run(array $data)
    {
        if (!($this->repository instanceof Repository)) {
            throw new RepositoryNotFoundException;
        }

        $id   = $this->authUserService->user()->id;
        $item = $this->repository->get($id);

        $entity = $this->repository->update($item, $data);

        return $entity;
    }
}

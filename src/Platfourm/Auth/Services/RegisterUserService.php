<?php

namespace App\Services\Auth;

use App\Exceptions\RepositoryNotFoundException;
use App\Repositories\Identity\UserRepository;
use App\Repositories\Repository;
use App\Services\EntityService;

class RegisterUserService extends EntityService
{
    protected $repository;
    protected $authUserService;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(array $data)
    {
        if (!($this->repository instanceof Repository)) {
            throw new RepositoryNotFoundException;
        }

        $data['password'] = bcrypt($data['password']);

        $entity = $this->repository->create($data);

        return $entity;
    }
}

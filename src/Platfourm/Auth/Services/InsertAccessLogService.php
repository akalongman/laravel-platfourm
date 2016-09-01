<?php

namespace Longman\Platfourm\Auth\Services;

use Longman\Platfourm\Auth\Repositories\Eloquent\AccessLogRepository;
use Longman\Platfourm\Contracts\Auth\AuthUserService as AuthUserServiceContract;
use Longman\Platfourm\Service\EntityService;

class InsertAccessLogService extends EntityService
{
    protected $repository;
    protected $authUserService;

    public function __construct(
        AuthUserServiceContract $authUserService,
        AccessLogRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;
    }

    public function run(array $data)
    {
        $this->checkRepository();

        $entity = $this->repository->create($data);

        return $entity;
    }
}

<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

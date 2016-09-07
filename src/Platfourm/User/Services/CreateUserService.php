<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\User\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Foundation\Repositories\Repository;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;

class CreateUserService extends EntityService
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

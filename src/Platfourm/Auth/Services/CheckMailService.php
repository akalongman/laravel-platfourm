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

use App\Contracts\Services\Auth\AuthUserService as AuthUserServiceContract;
use App\Exceptions\RepositoryNotFoundException;
use App\Repositories\Identity\UserRepository;
use App\Repositories\Repository;
use App\Services\EntityService;
use App\Services\ServiceDispatcher;

class CheckMailService extends EntityService
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

    public function run($mail)
    {
        if (!($this->repository instanceof Repository)) {
            throw new RepositoryNotFoundException;
        }

        $item = $this->repository->findBy('email', $mail);

        return $item;
    }
}

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

use Longman\Platfourm\Auth\Exceptions\ForbiddenException;
use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\User\Repositories\Eloquent\UserRepository;

class UpdateUserStatusService extends EntityService
{
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

    public function run($id, $state)
    {
        $item = $this->repository->find($id);

        if (!$this->authUserService->canUpdateUser($item)) {
            throw new ForbiddenException('Do not have permission to update this user');
        }

        $item->update(['status' => $state]);

        return $item;
    }
}

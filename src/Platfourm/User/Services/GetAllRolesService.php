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
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Service\Traits\FindAllEntities;
use Longman\Platfourm\User\Repositories\Eloquent\RoleRepository;

class GetAllRolesService extends EntityService
{
    use FindAllEntities;

    protected $repository;

    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        RoleRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        //$authUserService->should('role.*');
    }
}

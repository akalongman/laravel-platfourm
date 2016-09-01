<?php

namespace Longman\Platfourm\Text\Services;

use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Text\Repositories\Eloquent\TextRepository;

class UpdateTextValueService extends EntityService
{

    protected $repository;
    protected $authUserService;

    public function __construct(
        AuthUserService $authUserService,
        TextRepository $repository
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;

        $authUserService->should('text.update');
    }

    public function run(array $options, $value)
    {
        $this->checkRepository();

        $entity = $this->repository->updateValue($options, $value);

        return $this->parseResult($entity);
    }

}

<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Text\Services;

use Exception;
use Illuminate\Contracts\Config\Repository as Config;
use Longman\Platfourm\Contracts\Auth\AuthUserService;
use Longman\Platfourm\Service\EntityService;
use Longman\Platfourm\Text\Repositories\Eloquent\TextRepository;

class AutosaveTextsService extends EntityService
{
    protected $repository;
    protected $authUserService;
    protected $config;

    public function __construct(
        AuthUserService $authUserService,
        TextRepository $repository,
        Config $config
    ) {
        $this->authUserService = $authUserService;
        $this->repository      = $repository;
        $this->config          = $config;
    }

    public function run($lang, $scope, array $data)
    {
        $this->checkRepository();

        try {
            $locales = $this->config->get('multilang.locales', []);
            $status  = $this->repository->autosave($locales, $lang, $scope, $data);
        } catch (Exception $e) {
            return false;
        }

        return $status;
    }
}

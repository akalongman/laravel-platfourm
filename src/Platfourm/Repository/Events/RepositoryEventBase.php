<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Repository\Events;

use Illuminate\Database\Eloquent\Model;
use Longman\Platfourm\Contracts\Repository\Repository;

/**
 * Class RepositoryEventBase.
 */
abstract class RepositoryEventBase
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $action;

    /**
     * @param Repository $repository
     * @param Model      $model
     */
    public function __construct(Repository $repository, Model $model)
    {
        $this->repository = $repository;
        $this->model      = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}

<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Database\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Longman\Platfourm\Contracts\Auth\AuthUserService;

class Model extends Eloquent
{

    /**
     * The name of the "updated by" column.
     *
     * @var string
     */
    const CREATED_BY = 'created_by';

    /**
     * The name of the "updated by" column.
     *
     * @var string
     */
    const UPDATED_BY = 'updated_by';

    protected $searchableFields = [];

    protected $sortableFields = [];

    protected $filterableFields = [];

    /**
     * Get Searchable Fields.
     *
     * @return array
     */
    public function getSearchableFields()
    {
        return $this->searchableFields;
    }

    /**
     * Get Sortable Fields.
     *
     * @return array
     */
    public function getSortableFields()
    {
        return $this->sortableFields;
    }

    /**
     * Get Sortable Fields.
     *
     * @return array
     */
    public function getFilterableFields()
    {
        return $this->filterableFields;
    }

    /**
     * Update the creation and update timestamps.
     *
     * @return void
     */
    protected function updateTimestamps()
    {
        $time = $this->freshTimestamp();

        if (!$this->isDirty(static::UPDATED_AT)) {
            $this->setUpdatedAt($time);
            $this->setUpdatedBy();
        }

        if (!$this->exists && !$this->isDirty(static::CREATED_AT)) {
            $this->setCreatedAt($time);
            $this->setCreatedBy();
        }
    }

    /**
     * Set the value of the "updated by" attribute.
     *
     * @return $this
     */
    public function setUpdatedBy()
    {
        $userService = app()->make(AuthUserService::class);
        if ($userService->check()) {
            $this->{static::UPDATED_BY} = $userService->user()->id;
        }

        return $this;
    }

    /**
     * Set the value of the "created by" attribute.
     *
     * @return $this
     */
    public function setCreatedBy()
    {
        $userService = app()->make(AuthUserService::class);
        if ($userService->check()) {
            $this->{static::CREATED_BY} = $userService->user()->id;
        }

        return $this;
    }

}

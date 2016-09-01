<?php

namespace Longman\Platfourm\Repository\Criteria;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Longman\Platfourm\Contracts\Repository\Criteria;
use Longman\Platfourm\Contracts\Repository\Repository;

/**
 * Class RequestCriteria.
 */
class SearchCriteria implements Criteria
{
    protected $search;

    protected $searchFields;

    public function __construct($search, $searchFields = null)
    {
        $this->search       = $search;
        $this->searchFields = $searchFields;
    }

    /**
     * Apply criteria in query repository.
     *
     * @param             $model
     * @param  Repository $repository
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        if ($model instanceof EloquentBuilder) {
            $searchableFields = $model->getModel()->getSearchableFields();
        } else {
            $searchableFields = $model->getSearchableFields();
        }

        $search       = $this->search;
        $searchFields = $this->searchFields;
        $searchFields = !empty($searchFields) ? explode(',', $searchFields) : $searchableFields;

        if ($search && $searchableFields) {
            $model = $model->where(function ($query) use ($searchableFields, $searchFields, $search) {

                $isFirstField = true;

                foreach ($searchFields as $field) {
                    if (!in_array($field, $searchableFields)) {
                        continue;
                    }

                    $value = "%{$search}%";

                    if ($isFirstField && !is_null($value)) {
                        $query->where($field, 'like', $value);
                        $isFirstField = false;
                    } elseif (!is_null($value)) {
                        $query->orWhere($field, 'like', $value);
                    }
                }
            });
        }

        return $model;
    }

}

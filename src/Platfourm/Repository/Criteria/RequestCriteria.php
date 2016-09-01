<?php

namespace Longman\Platfourm\Repository\Criteria;

use Longman\Platfourm\Contracts\Repository\Criteria;
use Longman\Platfourm\Contracts\Repository\Repository;

/**
 * Class RequestCriteria.
 */
class RequestCriteria implements Criteria
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    protected $sortBy;

    protected $fields;

    protected $search;

    protected $searchFields;

    public function __construct($sortBy, $fields, $search, $searchFields)
    {
        $this->request      = app('request');
        $this->sortBy       = $sortBy;
        $this->fields       = $fields;
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
        $searchableFields = $model->getSearchableFields();
        $filterableFields = $model->getFilterableFields();
        $sortableFields   = $model->getSortableFields();
        $requestFields    = $this->request->all();

        $sortBy = $this->sortBy;
        $fields = $this->fields;

        $search       = $this->search;
        $searchFields = $this->searchFields;
        $searchFields = !empty($searchFields) ? explode(',', $searchFields) : $searchableFields;

        if ($search && $searchableFields) {
            $model = $model->where(function ($query) use ($requestFields, $searchableFields, $searchFields, $search) {

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

        if ($filterableFields) {
            $model = $model->where(function ($query) use ($requestFields, $filterableFields) {

                foreach ($filterableFields as $field => $condition) {
                    if (!isset($requestFields[$field])) {
                        continue;
                    }

                    $value = $requestFields[$field];
                    if (!is_null($value)) {
                        $query->where($field, $condition, $value);
                    }
                }
            });
        }

        if (!empty($sortBy)) {
            $sortBy = explode(',', $sortBy);
            foreach ($sortBy as $sortItem) {
                $sortEx    = explode(':', $sortItem);
                $sortField = isset($sortEx[0]) ? trim($sortEx[0]) : '';
                $sortDir   = isset($sortEx[1]) ? trim($sortEx[1]) : 'asc';
                if (!in_array($sortField, $sortableFields)) {
                    continue;
                }
                if (!in_array($sortDir, ['asc', 'desc'])) {
                    $sortDir = 'asc';
                }

                $model = $model->orderBy($sortField, $sortDir);
            }
        }

        if (!empty($fields)) {
            if (is_string($fields)) {
                $fields = explode(',', $fields);
            }

            $model = $model->select($fields);
        }

        return $model;
    }

    /**
     * @param  $search
     * @return array
     */
    protected function parserSearchData($search)
    {

        if (is_array($search)) {
            foreach ($search as $key => $value) {
                if (empty($value)) {
                    unset($search[$key]);
                }
            }

            return $search;
        }

        $searchData = [];

        if (stripos($search, ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }

    /**
     * @param  $search
     * @return null
     */
    protected function parserSearchValue($search)
    {

        if (is_array($search)) {
            return isset($search['q']) ? $search['q'] : null;
        }

        if (stripos($search, ';') || stripos($search, ':')) {
            $values = explode(';', $search);

            foreach ($values as $value) {
                $s = explode(':', $value);

                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return;
        }

        return $search;
    }

    protected function parserFieldsSearch(array $fields = [], array $searchFields = null)
    {

        if (!is_null($searchFields) && count($searchFields)) {
            $acceptedConditions = config('database.criteria.acceptedConditions', [
                '=',
                '>',
                '>=',
                '<',
                '<=',
                '!=',
                '<>',
                'like',
                'not like',
                'between',
                'not between',
                'in',
                'not in',
                'null',
                'not null'
            ]);
            $originalFields     = $fields;
            $fields             = [];

            foreach ($searchFields as $index => $field) {
                $field_parts = explode(':', $field);
                $_index      = array_search($field_parts[0], $originalFields);

                if (count($field_parts) == 2) {
                    if (in_array($field_parts[1], $acceptedConditions)) {
                        unset($originalFields[$_index]);
                        $field                  = $field_parts[0];
                        $condition              = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index]   = $field;
                    }
                }
            }

            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field     = $condition;
                    $condition = '=';
                }

                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }

            if (count($fields) == 0) {
                throw new \Exception(trans(
                    'database::criteria.fields_not_accepted',
                    ['field' => implode(',', $searchFields)]
                ));
            }
        }

        return $fields;
    }
}

<?php

namespace Longman\Platfourm\Repository\Eloquent;

use Closure;
use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Longman\Platfourm\Contracts\Repository\Criteria;
use Longman\Platfourm\Contracts\Repository\Presentable;
use Longman\Platfourm\Contracts\Repository\Presenter;
use Longman\Platfourm\Contracts\Repository\Repository;
use Longman\Platfourm\Contracts\Repository\RepositoryCriteria;
use Longman\Platfourm\Repository\Events\RepositoryEntityCreated;
use Longman\Platfourm\Repository\Events\RepositoryEntityDeleted;
use Longman\Platfourm\Repository\Events\RepositoryEntityRestored;
use Longman\Platfourm\Repository\Events\RepositoryEntityUpdated;
use Longman\Platfourm\Repository\Exceptions\RepositoryException;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository implements Repository, RepositoryCriteria
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Presenter
     */
    protected $presenter;

    /**
     * Validation Rules.
     *
     * @var array
     */
    protected $rules = null;

    /**
     * Collection of Criteria.
     *
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @var bool
     */
    protected $skipPresenter = false;

    /**
     * @var \Closure
     */
    protected $scopeQuery = null;

    protected $availableSortDirections = ['asc', 'desc'];

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app      = $app;
        $this->criteria = new Collection();
        $this->makeModel();
        $this->makePresenter();
        $this->boot();
    }

    public function boot()
    {
    }

    /**
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    abstract public function model();

    /**
     * Specify Presenter class name.
     *
     * @return string
     */
    public function presenter()
    {
    }

    /**
     * Set Presenter.
     *
     * @param  $presenter
     * @return $this
     */
    public function setPresenter($presenter)
    {
        $this->makePresenter($presenter);

        return $this;
    }

    /**
     * @throws RepositoryException
     * @return Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * @param  null $presenter
     * @throws RepositoryException
     * @return Presenter
     */
    public function makePresenter($presenter = null)
    {
        $presenter = !is_null($presenter) ? $presenter : $this->presenter();

        if (!is_null($presenter)) {
            $this->presenter = is_string($presenter) ? $this->app->make($presenter) : $presenter;

            if (!$this->presenter instanceof Presenter) {
                throw new RepositoryException("Class {$presenter} must be an instance of Longman\Platfourm\\Contracts\\Repository\\Presenter");
            }

            return $this->presenter;
        }
    }

    /**
     * Query Scope.
     *
     * @param  \Closure $scope
     * @return $this
     */
    public function scopeQuery(\Closure $scope)
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Retrieve count of records.
     *
     * @param  array $columns
     * @return mixed
     */
    public function count()
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($this->model instanceof EloquentBuilder) {
            $results = $this->model->count();
        } else {
            $results = $this->model->count();
        }

        $this->resetModel();

        return $results;
    }

    /**
     * Retrieve all data of repository.
     *
     * @param  array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($this->model instanceof EloquentBuilder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetModel();

        return $this->parserResult($results);
    }

    public function findAll($columns = ['*'])
    {

        return $this->all($columns);
    }

    /**
     * Retrieve first data of repository.
     *
     * @param  array $columns
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $results = $this->model->first($columns);
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Get entities
     *
     * @param string     $columns
     * @param array|null $options
     * @param null       $limit
     * @param null       $page
     * @param null       $sortBy
     * @return mixed
     */
    public function findBy($columns = '*', array $options = null, $limit = null, $page = null, $sortBy = null)
    {
        $this->applyCriteria();
        $this->applyScope();

        $model = $this->model;

        if (!is_array($columns)) {
            $columns = $this->parseColumns($columns);
        }

        if (!is_null($options)) {
            $model = $this->setWheres($model, $options);
        }

        if (is_string($sortBy)) {
            $sortBy = $this->parseSortByString($sortBy);
        }

        if (!empty($sortBy)) {
            if ($this->model instanceof EloquentBuilder) {
                $sortableFields = $this->model->getModel()->getSortableFields();
            } else {
                $sortableFields = $this->model->getSortableFields();
            }
            foreach ($sortBy as $field => $direction) {
                if (!in_array($field, $sortableFields)) {
                    continue;
                }
                if (!in_array($direction, $this->availableSortDirections)) {
                    $direction = 'asc';
                }
                $model = $model->orderBy($field, $direction);
            }
        }

        if (is_null($limit)) {
            $data    = $model->get($columns);
            $total   = $data->count();
            $results = new LengthAwarePaginator($data, $total, 1, 1, []);
        } else {
            $results = $model->paginate($limit, $columns, 'page', $page);
        }
        $this->resetModel();

        return $this->parserResult($results);
    }

    protected function parseColumns($columns)
    {
        if ($columns == '*') {
            return ['*'];
        }

        return explode(',', $columns);
    }

    protected function setWheres($model, array $options = null)
    {

        foreach ($options as $k => $v) {
            if ($v instanceof Closure) {
                $model = $model->where($v);
                continue;
            }

            $boolean = 'and';

            $ex = explode(':', $k);

            if (empty($ex[1])) {
                $operator = '=';
                $field    = trim($ex[0]);
            } else {
                $operator = trim($ex[0]);
                $field    = trim($ex[1]);
            }

            switch ($operator) {
                case 'in':
                    $model = $model->whereIn($field, $v, $boolean);
                    break;

                case 'not_in':
                    $model = $model->whereNotIn($field, $v, $boolean);
                    break;

                case '=':
                case '<':
                case '>':
                case '<=':
                case '>=':
                case '<>':
                case '!=':
                    $model = $model->where($field, $operator, $v, $boolean);
                    break;

                case 'not':
                    $model = $model->where($field, '!=', $v, $boolean);
                    break;

                case 'like':
                    $model = $model->where($field, 'like', $v, $boolean);
                    break;

                case 'not_like':
                    $model = $model->where($field, 'not like', $v, $boolean);
                    break;

                case 'between':
                    $model = $model->where($field, 'between', $v, $boolean);
                    break;
            }
        }
        return $model;
    }

    /**
     * Retrieve all data of repository, paginated.
     *
     * @param  null  $limit
     * @param  array $columns
     * @return mixed
     */
    public function paginate($columns = ['*'], $limit = null, $page = null, $sortBy = [])
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('database.pagination.limit', 15) : $limit;

        if (is_string($sortBy)) {
            $sortBy = $this->parseSortByString($sortBy);
        }

        if (!empty($sortBy)) {
            $sortableFields = $model->getSortableFields();
            foreach ($sortBy as $field => $direction) {
                if (!in_array($field, $sortableFields)) {
                    continue;
                }
                if (!in_array($sortDir, $this->availableSortDirections)) {
                    $sortDir = 'asc';
                }
                $this->model = $this->model->orderBy($sortField, $sortDir);
            }
        }

        $results = $this->model->paginate($limit, $columns, 'page', $page);
        $this->resetModel();

        return $this->parserResult($results);
    }

    protected function parseSortByString($sortBy)
    {
        $sortBy = explode(',', $sortBy);
        $return = [];
        foreach ($sortBy as $sortItem) {
            $sortEx = explode(':', $sortItem);
            if (empty($sortEx[0])) {
                continue;
            }
            $sortField = trim($sortEx[0]);
            $sortDir   = isset($sortEx[1]) ? trim($sortEx[1]) : 'asc';
            if (!in_array($sortDir, $this->availableSortDirections)) {
                $sortDir = 'asc';
            }
            $return[$sortField] = $sortDir;
        }
        return $return;
    }

    /**
     * Retrieve all data of repository, simple paginated.
     *
     * @param  null  $limit
     * @param  array $columns
     * @return mixed
     */
    public function simplePaginate($limit = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit   = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->simplePaginate($limit, $columns, 'page', $page);
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Find data by id.
     *
     * @param        $id
     * @param  array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findOrFail($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by id or return new if not exists.
     *
     * @param        $id
     * @param  array $columns
     * @return mixed
     */
    public function findOrNew($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        try {
            $model = $this->model->findOrFail($id, $columns);
        } catch (Exception $e) {
            $model = $this->model->newInstance([]);
        }

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by field and value.
     *
     * @param        $field
     * @param        $value
     * @param  array $columns
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->where($field, '=', $value)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by slug.
     *
     * @param        $value
     * @param  array $columns
     * @return mixed
     */
    public function findBySlug($value = null, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->whereSlug($value)->first($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by multiple fields.
     *
     * @param  array $where
     * @param  array $columns
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }

        $model = $this->model->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by multiple values in one field.
     *
     * @param        $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        $this->applyCriteria();
        $model = $this->model->whereIn($field, $values)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Find data by excluding multiple values in one field.
     *
     * @param        $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        $this->applyCriteria();
        $model = $this->model->whereNotIn($field, $values)->get($columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Create a new instance in repository.
     *
     * @param  array $attributes
     * @return mixed
     */
    public function newInstance(array $attributes)
    {
        $model = $this->model->newInstance($attributes);

        $this->resetModel();

        return $this->parserResult($model);
    }

    /**
     * Save a new entity in repository.
     *
     * @param  array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        $this->resetModel();

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Update a entity in repository by id.
     *
     * @param  array $attributes
     * @param        $id
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        $this->applyScope();
        $_skipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();

        $this->skipPresenter($_skipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Delete a entity in repository by id.
     *
     * @param  $id
     * @return int
     */
    public function delete($id)
    {
        $this->applyScope();

        $_skipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model         = $this->find($id);
        $originalModel = clone $model;

        $this->skipPresenter($_skipPresenter);
        $this->resetModel();

        $deleted = $model->delete();

        event(new RepositoryEntityDeleted($this, $originalModel));

        return $this->parserResult($model);
    }

    /**
     * Delete a entity in repository by id.
     *
     * @param  $id
     * @return int
     */
    public function restore($id)
    {
        $this->applyScope();
        $_skipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->withTrashed()->findOrFail($id);

        if (!$model->trashed()) {
            return $model;
        }

        $restored = $model->restore();

        $this->skipPresenter($_skipPresenter);
        $this->resetModel();

        event(new RepositoryEntityRestored($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Load relations.
     *
     * @param  array|string $relations
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    /**
     * With trashed.
     *
     * @return $this
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();

        return $this;
    }

    /**
     * Set hidden fields.
     *
     * @param  array $fields
     * @return $this
     */
    public function hidden(array $fields)
    {
        $this->model->setHidden($fields);

        return $this;
    }

    /**
     * Set visible fields.
     *
     * @param  array $fields
     * @return $this
     */
    public function visible(array $fields)
    {
        $this->model->setVisible($fields);

        return $this;
    }

    /**
     * Push Criteria for filter the query.
     *
     * @param  Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * Get Collection of Criteria.
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Find data by Criteria.
     *
     * @param  Criteria $criteria
     * @return mixed
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        $results     = $this->model->get();
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Skip Criteria.
     *
     * @param  bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * Apply scope in current Query.
     *
     * @return $this
     */
    protected function applyScope()
    {

        if (isset($this->scopeQuery) && is_callable($this->scopeQuery)) {
            $callback    = $this->scopeQuery;
            $this->model = $callback($this->model);
        }

        return $this;
    }

    /**
     * Apply criteria in current Query.
     *
     * @return $this
     */
    protected function applyCriteria()
    {

        if ($this->skipCriteria === true) {
            return $this;
        }

        $criteria = $this->getCriteria();

        if ($criteria) {
            foreach ($criteria as $c) {
                if ($c instanceof Criteria) {
                    $this->model = $c->apply($this->model, $this);
                }
            }
        }

        return $this;
    }

    /**
     * Skip Presenter Wrapper.
     *
     * @param  bool $status
     * @return $this
     */
    public function skipPresenter($status = true)
    {
        $this->skipPresenter = $status;

        return $this;
    }

    /**
     * Wrapper result data.
     *
     * @param  mixed $result
     * @return mixed
     */
    public function parserResult($result)
    {

        if ($this->presenter instanceof Presenter) {
            if ($result instanceof Collection || $result instanceof LengthAwarePaginator) {
                $result->each(function ($model) {

                    if ($model instanceof Presentable) {
                        $model->setPresenter($this->presenter);
                    }

                    return $model;
                });
            } elseif ($result instanceof Presentable) {
                $result = $result->setPresenter($this->presenter);
            }

            if (!$this->skipPresenter) {
                return $this->presenter->present($result);
            }
        }

        return $result;
    }
}

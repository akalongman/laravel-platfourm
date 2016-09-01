<?php

namespace Longman\Platfourm\Database\Eloquent\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Longman\Platfourm\Contracts\Auth\AuthUserService;

class SoftDeletingScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['ForceDelete', 'Restore', 'WithTrashed', 'OnlyTrashed'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull($model->getQualifiedDeletedAtColumn());
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {

            $update = [
                $this->getDeletedAtColumn($builder) => $builder->getModel()->freshTimestampString(),
            ];

            $userService = app()->make(AuthUserService::class);
            if ($userService->check()) {
                $update[$this->getDeletedByColumn($builder)] = $userService->user()->id;
            }

            return $builder->update($update);
        });
    }

    /**
     * Get the "deleted at" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return string
     */
    protected function getDeletedAtColumn(Builder $builder)
    {
        if (count($builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDeletedAtColumn();
        } else {
            return $builder->getModel()->getDeletedAtColumn();
        }
    }

    /**
     * Get the "deleted by" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return string
     */
    protected function getDeletedByColumn(Builder $builder)
    {
        if (count($builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDeletedByColumn();
        } else {
            return $builder->getModel()->getDeletedByColumn();
        }
    }

    /**
     * Add the force delete extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addForceDelete(Builder $builder)
    {
        $builder->macro('forceDelete', function (Builder $builder) {
            return $builder->getQuery()->delete();
        });
    }

    /**
     * Add the restore extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([$builder->getModel()->getDeletedAtColumn() => null]);
        });
    }

    /**
     * Add the with-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addWithTrashed(Builder $builder)
    {
        $builder->macro('withTrashed', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * Add the only-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->whereNotNull(
                $model->getQualifiedDeletedAtColumn()
            );

            return $builder;
        });
    }
}

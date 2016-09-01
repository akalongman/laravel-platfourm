<?php

namespace Longman\Platfourm\Database\Eloquent\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ArrayAsPrimary
{

    /**
     * Get key name
     *
     * @return mixed
     */
    public function getKeyName()
    {
        return $this->primaryKey[0];
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $key = $this->getKeyName();

        if (is_array($key)) {
            foreach ($key as $k) {
                $query->where($k, '=', $this->getKeyValueForSaveQuery($k));
            }
        } else {
            $query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @return mixed
     */
    protected function getKeyValueForSaveQuery($key)
    {
        if (isset($this->original[$key])) {
            return $this->original[$key];
        }

        return $this->getAttribute($key);
    }

}

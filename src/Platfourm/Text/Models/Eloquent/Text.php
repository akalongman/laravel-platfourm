<?php

namespace Longman\Platfourm\Text\Models\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Longman\Platfourm\Database\Eloquent\ActionLog\ActionLogTrait;
use Longman\Platfourm\Database\Eloquent\Model;

class Text extends Model
{
    use ActionLogTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'lang', 'value', 'scope'];

    public $incrementing = false;

    protected $primaryKey = ['key', 'lang', 'scope'];

    protected $searchableFields = ['key', 'value'];

    protected $sortableFields = ['key', 'value'];

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

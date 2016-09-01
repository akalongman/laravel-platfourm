<?php

namespace Longman\Platfourm\Text\Repositories\Eloquent;

use Longman\Platfourm\Contracts\Repository\Repository;
use Longman\Platfourm\Contracts\Repository\RepositoryCriteria;
use Longman\Platfourm\Repository\Eloquent\BaseRepository;
use Longman\Platfourm\Repository\Events\RepositoryEntityUpdated;
use Longman\Platfourm\Text\Models\Eloquent\Text;

class TextRepository extends BaseRepository implements Repository, RepositoryCriteria
{

    public function model()
    {
        if (class_exists(\App\Models\Text::class)) {
            return \App\Models\Text::class;
        }

        return Text::class;
    }

    /**
     * Update a entity in repository by id.
     *
     * @param  array $attributes
     * @param        $id
     * @return mixed
     */
    public function updateValue(array $options, $value)
    {
        $this->applyScope();
        $_skipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->where($options)->first();

        $model->setAttribute('value', $value);

        $model->saveOrFail();

        $this->skipPresenter($_skipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    public function autosave($locales, $lang, $scope, array $data)
    {
        $ins          = [];
        $ins['scope'] = $scope;
        foreach ($data as $key) {
            foreach ($locales as $locale => $locale_data) {
                $model  = $this->model->newInstance();
                $exists = $model->where(['key' => $key, 'lang' => $locale])->exists();
                if ($exists) {
                    continue;
                }

                $ins['key']   = $key;
                $ins['lang']  = $locale;
                $ins['value'] = $key;
                $model->fill($ins);
                $model->save();
                $this->resetModel();
            }
        }
        return true;
    }

}

<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Repository\Traits;

use Illuminate\Support\Arr;
use Longman\Platfourm\Contracts\Repository\Presenter;

/**
 * Class PresentableTrait.
 */
trait PresentableTrait
{
    /**
     * @var Presenter
     */
    protected $presenter = null;

    /**
     * @param \Longman\Platfourm\Contracts\Repository\Presenter $presenter
     *
     * @return $this
     */
    public function setPresenter(Presenter $presenter)
    {
        $this->presenter = $presenter;

        return $this;
    }

    /**
     * @return $this|mixed
     */
    public function presenter()
    {

        if ($this->hasPresenter()) {
            return $this->presenter->present($this);
        }

        return $this;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function present($key, $default = null)
    {

        if ($this->hasPresenter()) {
            $data = $this->presenter()['data'];

            return Arr::get($data, $key, $default);
        }

        return $default;
    }

    /**
     * @return bool
     */
    protected function hasPresenter()
    {
        return isset($this->presenter) && $this->presenter instanceof Presenter;
    }

}

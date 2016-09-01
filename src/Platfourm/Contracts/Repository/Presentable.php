<?php

namespace Longman\Platfourm\Contracts\Repository;

/**
 *  Presentable.
 */
interface Presentable
{
    /**
     * @param Presenter $presenter
     *
     * @return mixed
     */
    public function setPresenter(Presenter $presenter);

    /**
     * @return mixed
     */
    public function presenter();
}

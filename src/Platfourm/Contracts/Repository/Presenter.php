<?php

namespace Longman\Platfourm\Contracts\Repository;

/**
 *  Presenter.
 */
interface Presenter
{
    /**
     * Prepare data to present.
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}

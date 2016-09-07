<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Service\Traits;

use Exception;

trait RestoreEntityByKey
{

    public function run($id)
    {
        $this->checkRepository();

        $item = $this->repository->withTrashed()->find($id);

        if (!$item->trashed()) {
            throw new Exception('Entity is not trashed');
        }

        $item = $this->repository->restore($id);

        return $this->parseResult($item);
    }
}

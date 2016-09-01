<?php

namespace Longman\Platfourm\Repository\Transformer;

use League\Fractal\TransformerAbstract;
use Longman\Platfourm\Contracts\Repository\Transformable;

/**
 * Class ModelTransformer.
 */
class ModelTransformer extends TransformerAbstract
{
    public function transform(Transformable $model)
    {
        return $model->transform();
    }
}

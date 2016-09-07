<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Service;

use InvalidArgumentException;
use Longman\Platfourm\Contracts\Repository\Repository;

abstract class EntityService
{

    protected function checkRepository()
    {
        if (!($this->repository instanceof Repository)) {
            throw new RepositoryNotFoundException;
        }
    }

    protected function parseResult($result)
    {
        return $result;
    }

    /**
     * @param  array $input
     * @throws InvalidValueException
     * @return mixed
     */
    public function dispatch(array $input, $methodName = 'run')
    {
        $method = new ReflectionMethod($this, $methodName);
        $params = $method->getParameters();
        //var_dump($params);
        $args = [];
        if (!empty($params)) {
            foreach ($params as $item) {
                if (!isset($input[$item->getName()])) {
                    if ($item->isDefaultValueAvailable()) {
                        $input[$item->getName()] = $item->getDefaultValue();
                    } else {
                        throw new InvalidArgumentException("field not found " . $item->getName());
                    }
                }

                $args[] = $input[$item->getName()];
            }
        }

        return call_user_func_array([$this, $methodName], $args);
    }
}

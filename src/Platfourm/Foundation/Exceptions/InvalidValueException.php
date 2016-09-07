<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Foundation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidValueException extends HttpException
{

    public function __construct(
        $message = null,
        $statusCode = 400,
        \Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        if (empty($message)) {
            $trace   = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
            $message = '"invalid value on ' . $trace[0]['file'] . ' line ' . $trace[0]['line'] . '"';
        } else {
            $message = '"' . $message . '"';
        }
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}

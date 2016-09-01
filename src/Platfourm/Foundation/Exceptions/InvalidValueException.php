<?php

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

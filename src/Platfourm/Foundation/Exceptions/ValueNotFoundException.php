<?php

namespace Longman\Platfourm\Foundation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ValueNotFoundException extends HttpException
{
    public function __construct(
        $message = null,
        $statusCode = 204,
        \Exception $previous = null,
        array $headers = array(),
        $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
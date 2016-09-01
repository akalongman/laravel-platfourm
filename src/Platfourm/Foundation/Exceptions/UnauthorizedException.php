<?php

namespace Longman\Platfourm\Foundation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    public function __construct(
        $message = null,
        $statusCode = 401,
        \Exception $previous = null,
        array $headers = array(),
        $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}

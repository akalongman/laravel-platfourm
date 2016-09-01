<?php

namespace Longman\Platfourm\Auth\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ForbiddenException extends HttpException
{
    public function __construct(
        $message = null,
        $statusCode = 403,
        \Exception $previous = null,
        array $headers = array(),
        $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}

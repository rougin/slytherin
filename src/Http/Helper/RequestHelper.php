<?php

namespace Rougin\Slytherin\Http\Helper;

use InvalidArgumentException;

class RequestHelper
{
    /**
     * Supported HTTP methods
     *
     * @var array
     */
    private $validMethods = [
        'CONNECT',
        'DELETE',
        'GET',
        'HEAD',
        'OPTIONS',
        'PATCH',
        'POST',
        'PUT',
        'TRACE',
    ];

    /**
     * Validate the HTTP method
     *
     * @param  null|string $method
     * @throws InvalidArgumentException on invalid HTTP method.
     */
    public static function validateMethod($method)
    {
        if ($method === null) {
            return true;
        }

        if (! is_string($method)) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported HTTP method; must be a string, received %s',
                (is_object($method) ? get_class($method) : gettype($method))
            ));
        }

        $method = strtoupper($method);

        if (! in_array($method, $this->validMethods, true)) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported HTTP method "%s" provided',
                $method
            ));
        }
    }
}
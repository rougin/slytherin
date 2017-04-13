<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;

/**
 * Middleware
 *
 * A simple implementation of a middleware on PSR-15.
 * NOTE: To be removed in v1.0.0. Use "Middleware\Dispatcher" instead.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class Middleware extends \Rougin\Slytherin\Middleware\Middleware
{
}

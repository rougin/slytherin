<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Middleware Interface
 *
 * An interface for handling third party middlewares.
 * NOTE: To be removed in v1.0.0. Use DispatcherInterface instead.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface MiddlewareInterface extends \Interop\Http\ServerMiddleware\MiddlewareInterface
{
}

<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Dispatcher Interface
 *
 * An interface for handling third-party middleware dispatchers.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface DispatcherInterface extends MiddlewareInterface
{
    /**
     * Returns the list of added middlewares.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    public function getStack();

    /**
     * Add a new middleware to the end of the stack.
     *
     * @param  mixed $middleware
     * @return self
     */
    public function push($middleware);

    /**
     * Sets a new stack of middlewares.
     *
     * @param  mixed[] $stack
     * @return self
     */
    public function setStack($stack);
}

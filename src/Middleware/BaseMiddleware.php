<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Base Middleware
 *
 * Serves as a foundation for creating middlewares for Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class BaseMiddleware
{
    /**
     * @var array
     */
    protected $stack = array();

    /**
     * Adds a new middleware in the stack.
     *
     * @param  callable|object $middleware
     * @return self
     */
    public function push($middleware)
    {
        array_push($this->stack, $middleware);

        return $this;
    }

    /**
     * Returns the listing of middlewares included.
     *
     * @return array
     */
    public function getQueue()
    {
        return $this->stack;
    }
}

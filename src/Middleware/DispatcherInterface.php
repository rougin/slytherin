<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Dispatcher Interface
 *
 * An interface for handling third party middleware dispatchers.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface DispatcherInterface extends MiddlewareInterface
{
    /**
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface[]
     */
    public function getStack();

    /**
     * @param  mixed $middleware
     * @return self
     */
    public function push($middleware);

    /**
     * @param  mixed[] $stack
     * @return self
     */
    public function setStack($stack);
}

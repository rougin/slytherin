<?php

namespace Rougin\Slytherin\Server;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface DispatchInterface extends MiddlewareInterface
{
    /**
     * @return \Rougin\Slytherin\Server\MiddlewareInterface[]
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

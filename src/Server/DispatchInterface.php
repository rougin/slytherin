<?php

namespace Rougin\Slytherin\Server;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface DispatchInterface extends MiddlewareInterface
{
    /**
     * @return mixed[]
     */
    public function getStack();

    /**
     * @param  mixed[] $stack
     * @return self
     */
    public function setStack($stack);
}

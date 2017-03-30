<?php

namespace Rougin\Slytherin\Container;

/**
 * Container Interface
 *
 * An interface for handling third party dependency injection containers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * Sets a new instance to the container.
     *
     * @param  string     $alias
     * @param  mixed|null $concrete
     * @return self
     */
    public function set($alias, $concrete = null);
}

<?php

namespace Rougin\Slytherin\Container;

/**
 * Container Interface
 *
 * An interface for handling third party dependency injection containers.
 *
 * PSR-11 includes how to get instances from containers using the "get" method.
 * But they don't have yet a unified implementation for defining objects. With
 * this problem, Slytherin will provide an interface that includes a "set"
 * method and this can be served as a "temporary" workaround until the said
 * package, "container-interop/container-interop", will be updated for this matter.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ContainerInterface extends \Interop\Container\ContainerInterface
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

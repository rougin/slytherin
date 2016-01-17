<?php

namespace Rougin\Slytherin\IoC;

use Interop\Container\ContainerInterface as InteropContainerInterface;

/**
 * Dependency Injection Container Interface
 *
 * An interface for handling third party dependency injection containers.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ContainerInterface extends InteropContainerInterface
{
    /**
     * Adds a new instance to the container.
     * 
     * @param string  $id
     * @param mixed   $concrete
     */
    public function add($id, $concrete = null);
}

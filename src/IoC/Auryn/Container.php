<?php

namespace Rougin\Slytherin\IoC\Auryn;

/**
 * Auryn Container
 *
 * A simple implementation of a container that is built on top of
 * Daniel Lowrey's Auryn Inversion Of Control (IoC) Dependency Injector.
 *
 * https://github.com/rdlowrey/auryn
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container extends \Rougin\Slytherin\IoC\BaseContainer implements \Interop\Container\ContainerInterface
{
    /**
     * @var \Auryn\Injector
     */
    public $injector;

    /**
     * @param \Auryn\Injector $injector
     */
    public function __construct(\Auryn\Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * Adds a new instance to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     */
    public function add($id, $concrete = null)
    {
        if ($concrete && ! is_array($concrete)) {
            $this->instances[$id] = $concrete;

            return $this;
        }

        $arguments = [];

        if (is_array($concrete)) {
            $arguments = $concrete;
        }

        $this->instances[$id] = $this->injector->make($id, $arguments);

        return $this;
    }
}

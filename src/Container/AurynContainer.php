<?php

namespace Rougin\Slytherin\Container;

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
class AurynContainer extends VanillaContainer
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
     * Sets a new instance to the container.
     *
     * @param string     $alias
     * @param mixed|null $concrete
     */
    public function set($alias, $concrete = null)
    {
        if ($concrete && ! is_array($concrete)) {
            $this->instances[$alias] = $concrete;

            return $this;
        }

        $arguments = array();

        if (is_array($concrete)) {
            $arguments = $concrete;
        }

        $this->instances[$alias] = $this->injector->make($alias, $arguments);

        return $this;
    }
}

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
class AurynContainer extends Container
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
     * @param  string $id
     * @param  mixed  $concrete
     * @return self
     */
    public function set($id, $concrete)
    {
        if ($concrete && ! is_array($concrete)) {
            $this->instances[$id] = $concrete;

            return $this;
        }

        $arguments = (is_array($concrete)) ? $concrete : array();

        $this->instances[$id] = $this->injector->make($id, $arguments);

        return $this;
    }
}

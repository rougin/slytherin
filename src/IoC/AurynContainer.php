<?php

namespace Rougin\Slytherin\IoC;

use Auryn\Injector;

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
class AurynContainer extends BaseContainer implements DependencyInjectorInterface
{
    /**
     * @var \Auryn\Injector
     */
    public $injector;

    /**
     * @param \Auryn\Injector $injector
     */
    public function __construct(Injector $injector)
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

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->injector, $method), $args);
    }
}

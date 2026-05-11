<?php

namespace Rougin\Slytherin\Container;

Interop::register('AurynContainer');

/**
 * @package Slytherin
 *
 * @method mixed                                          get(string $id)
 * @method boolean                                        has(string $id)
 * @method \Rougin\Slytherin\Container\ContainerInterface set(string $id, $concrete)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainer extends PsrAurynContainer
{
    /**
     * @param \Auryn\Injector $injector
     */
    public function __construct(\Auryn\Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @deprecated since ~0.9, use "set" instead.
     *
     * Adds a new instance to the container.
     *
     * @param string     $id
     * @param mixed|null $concrete
     *
     * @return self
     */
    public function add($id, $concrete = null)
    {
        $this->set($id, $concrete);

        return $this;
    }

    /**
     * Calls methods from the \Auryn\Injector instance.
     *
     * @param string  $method
     * @param mixed[] $params
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        /** @var callable $class */
        $class = array($this->injector, $method);

        return call_user_func_array($class, $params);
    }
}

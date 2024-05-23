<?php

namespace Rougin\Slytherin\Container;

use Auryn\Injector;

/**
 * Auryn Container
 *
 * A simple implementation of a container that is built on top of
 * Daniel Lowrey's Auryn Inversion Of Control (IoC) Dependency Injector.
 *
 * @package Slytherin
 *
 * @author Matthew Turland <me@matthewturland.com>
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://github.com/rdlowrey/auryn
 * @link https://github.com/elazar/auryn-container-interop
 *
 * @method alias($original, $alias)
 * @method share($nameOrInstance)
 */
class AurynContainer implements ContainerInterface
{
    /**
     * @var array<string, boolean>
     */
    protected $has = array();

    /**
     * @var \Auryn\Injector
     */
    protected $injector;

    /**
     * @var array<string, mixed>
     */
    protected $items = array();

    /**
     * @param \Auryn\Injector $injector
     */
    public function __construct(Injector $injector)
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
        return $this->set($id, $concrete);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id
     *
     * @return mixed
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function get($id)
    {
        if (! $this->has($id))
        {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        if (array_key_exists($id, $this->items))
        {
            return $this->items[$id];
        }

        return $this->injector->make($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has($id)
    {
        if (array_key_exists($id, $this->items))
        {
            return true;
        }

        $filter = Injector::I_BINDINGS | Injector::I_DELEGATES;

        $filter = $filter | Injector::I_PREPARES | Injector::I_ALIASES;

        $filter = $filter | Injector::I_SHARES;

        $definitions = $this->injector->inspect($id, $filter);

        $definitions = array_filter($definitions);

        return ! empty($definitions) ?: class_exists($id);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     *
     * @return self
     */
    public function set($id, $concrete)
    {
        $params = is_array($concrete) ? $concrete : array();

        if (! $concrete || is_array($concrete))
        {
            $concrete = $this->injector->make($id, $params);
        }

        $this->items[$id] = $concrete;

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

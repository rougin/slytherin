<?php

namespace Rougin\Slytherin\Container;

use Auryn\Injector;

/**
 * Auryn Container
 *
 * A simple implementation of a container that is built on top of
 * Daniel Lowrey's Auryn Inversion Of Control (IoC) Dependency Injector.
 *
 * https://github.com/rdlowrey/auryn
 * https://github.com/elazar/auryn-container-interop
 *
 * @package Slytherin
 * @author  Matthew Turland <me@matthewturland.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class AurynContainer extends Injector implements ContainerInterface
{
    /**
     * @var array
     */
    protected $has = array();

    /**
     * @var array
     */
    protected $instances = array();

    /**
     * @param \Auryn\Injector|\Auryn\Reflector|null $data
     */
    public function __construct($data = null)
    {
        is_a($data, 'Auryn\Injector') || parent::__construct($data);
    }

    /**
     * Adds a new instance to the container.
     * NOTE: To be removed in v1.0.0. Use $this->set() instead.
     *
     * @param  string     $id
     * @param  mixed|null $concrete
     * @return self
     */
    public function add($id, $concrete = null)
    {
        return $this->set($id, $concrete);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @param  string $id
     * @return mixed
     */
    public function get($id)
    {
        if (! $this->has($id)) {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        return $this->resolve($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        $filter = Injector::I_BINDINGS | Injector::I_DELEGATES | Injector::I_PREPARES | Injector::I_ALIASES | Injector::I_SHARES;

        if (! isset($this->has[$id])) {
            $definitions = array_filter($this->inspect($id, $filter));

            if (! empty($definitions)) {
                return $this->has[$id] = true;
            }

            if (! class_exists($id)) {
                return $this->has[$id] = false;
            }

            $reflector = new \ReflectionClass($id);

            return $this->has[$id] = $reflector->isInstantiable();
        }

        return $this->has[$id];
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
        $this->instances[$id] = $concrete;

        return $this;
    }

    /**
     * Resolves the specified identifier to an instance.
     *
     * @param  string $id
     * @return mixed
     */
    protected function resolve($id)
    {
        $entry = null;

        ! isset($this->instances[$id]) || $entry = $this->instances[$id];

        isset($entry) || $entry = $this->make($id);

        return $entry;
    }
}

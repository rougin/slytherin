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
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainer extends Injector implements ContainerInterface
{
    /**
     * @var array
     */
    protected $has = array();

    /**
     * @var \Auryn\Injector
     */
    protected $injector;

    /**
     * @var array
     */
    protected $instances = array();

    /**
     * Initializes the container instance.
     * NOTE: To be removed in v1.0.0. It should use \Auryn\Injector::__construct.
     *
     * @param \Auryn\Injector|\Auryn\Reflector|null $data
     */
    public function __construct($data = null)
    {
        $this->injector = $data;

        if (! $data instanceof Injector)
        {
            parent::__construct($data) && $this->injector = $this;
        }
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
        $entry = null;

        $this->has($id) && $entry = $this->resolve($id);

        if (is_null($entry))
        {
            $message = 'Alias (%s) is not being managed by the container';

            $message = (string) sprintf($message, $id);

            throw new Exception\NotFoundException($message);
        }

        return $entry;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        $exists = isset($this->has[$id]) ? $this->has[$id] : false;

        if (isset($this->has[$id])) return $exists;

        $filter = Injector::I_BINDINGS | Injector::I_DELEGATES;

        $filter = $filter | Injector::I_PREPARES | Injector::I_ALIASES;

        $filter = $filter | Injector::I_SHARES;

        $definitions = $this->injector->inspect($id, $filter);

        $definitions = array_filter($definitions);

        $exists = ! empty($definitions) ?: class_exists($id);

        return $exists;
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

        isset($this->instances[$id]) && $entry = $this->instances[$id];

        isset($entry) === false && $entry = $this->injector->make($id);

        return $entry;
    }
}
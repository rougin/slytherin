<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Container
 *
 * A simple container that is implemented on \Psr\Container\ContainerInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $extra;

    /**
     * NOTE: To be removed in v1.0.0. Use "protected" visibility instead.
     *
     * @var array
     */
    public $instances = array();

    /**
     * @param array $instances
     */
    public function __construct(array $instances = array(), PsrContainerInterface $container = null)
    {
        $this->instances = $instances;

        $this->extra = $container ?: new ReflectionContainer;
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
     * Creates an alias for a specified class.
     *
     * @param string $id
     * @param string $original
     */
    public function alias($id, $original)
    {
        $this->instances[$id] = $this->get($original);

        return $this;
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

        ! isset($this->instances[$id]) || $entry = $this->instances[$id];

        isset($entry) || $entry = $this->resolve($id);

        if (! is_callable($entry) && ! is_object($entry)) {
            $message = 'Alias (%s) is not a callable or an object';

            throw new Exception\ContainerException(sprintf($message, $id));
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
        return isset($this->instances[$id]) || $this->extra->has($id);
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
     * Returns an argument based on the given parameter.
     *
     * @param  \ReflectionParameter $parameter
     * @return mixed
     */
    protected function argument(\ReflectionParameter $parameter)
    {
        if ($parameter->isOptional() === false) {
            $name = $parameter->getClass() ? $parameter->getClass()->getName() : $parameter->getName();

            return $this->has($name) ? $this->get($name) : $this->extra->get($name);
        }

        return $parameter->getDefaultValue();
    }

    /**
     * Resolves the specified identifier to an instance.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @param  string $id
     * @return mixed
     */
    protected function resolve($id)
    {
        $reflection = new \ReflectionClass($id);

        if ($constructor = $reflection->getConstructor()) {
            $arguments = array();

            foreach ($constructor->getParameters() as $key => $parameter) {
                $name = $parameter->getName();

                $argument = $this->argument($parameter);

                $arguments[$key] = $argument ?: $parameters[$name];
            }

            return $reflection->newInstanceArgs($arguments);
        }

        return $this->extra ? $this->extra->get($id) : null;
    }
}

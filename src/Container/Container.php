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
     * @var array
     */
    public $instances = array();

    /**
     * @param array $instances
     */
    public function __construct(array $instances = array())
    {
        $this->instances = $instances;
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

        $entry = $this->instances[$id];

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
        return isset($this->instances[$id]);
    }

    /**
     * Resolves the dependencies on the specified class.
     *
     * @link   http://goo.gl/wN8Vaz
     * @param  string                                $class
     * @param  \Psr\ContainerContainerInterface|null $container
     * @return mixed
     */
    public function resolve($class, PsrContainerInterface $container = null)
    {
        $reflection = new \ReflectionClass($class);

        if ($constructor = $reflection->getConstructor()) {
            $arguments = array();

            foreach ($constructor->getParameters() as $parameter) {
                $argument = $this->argument($parameter, $container);

                array_push($arguments, $argument);
            }

            return $reflection->newInstanceArgs($arguments);
        }

        return new $class;
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string     $id
     * @param  mixed|null $concrete
     * @return self
     */
    public function set($id, $concrete = null)
    {
        $this->instances[$id] = $concrete;

        return $this;
    }

    /**
     * Returns an argument based on the given parameter.
     *
     * @param  \ReflectionParameter                  $parameter
     * @param  \Psr\ContainerContainerInterface|null $container
     * @return mixed
     */
    protected function argument(\ReflectionParameter $parameter, PsrContainerInterface $container = null)
    {
        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        $name = $parameter->getClass()->getName();

        if ($container && $container->has($name)) {
            return $container->get($name);
        }

        return $this->resolve($name);
    }
}

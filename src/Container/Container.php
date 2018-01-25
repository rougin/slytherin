<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * Initializes the container instance.
     *
     * @param array                                  $instances
     * @param \Psr\Container\ContainerInterface|null $container
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
     * Resolves the specified parameters from a container.
     *
     * @param  \ReflectionFunctionAbstract $reflector
     * @param  array                       $parameters
     * @return array
     */
    public function arguments(\ReflectionFunctionAbstract $reflector, $parameters = array())
    {
        $arguments = array();

        foreach ($reflector->getParameters() as $key => $parameter) {
            $argument = $this->argument($parameter);

            $name = $parameter->getName();

            $arguments[$key] = $argument ?: $parameters[$name];
        }

        return $arguments;
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
        if ($this->has($id) === false) {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        $entry = isset($this->instances[$id]) ? $this->instances[$id] : $this->resolve($id);

        if (is_object($entry) === false) {
            $message = 'Alias (%s) is not an object';

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
     * Resolves the specified identifier to an instance.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @param  string                                        $id
     * @param  \Psr\Http\Message\ServerRequestInterface|null $request
     * @return mixed
     */
    public function resolve($id, ServerRequestInterface $request = null)
    {
        $reflection = new \ReflectionClass($id);

        if ($constructor = $reflection->getConstructor()) {
            $arguments = array();

            foreach ($constructor->getParameters() as $parameter) {
                $argument = $this->argument($parameter);

                array_push($arguments, $this->request($argument, $request));
            }

            return $reflection->newInstanceArgs($arguments);
        }

        return $this->extra->get($id);
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
     * @return mixed|null
     */
    protected function argument(\ReflectionParameter $parameter)
    {
        try {
            $argument = $parameter->getDefaultValue();
        } catch (\ReflectionException $exception) {
            $class = $parameter->getClass();

            $exists = $parameter->getClass() !== null;

            $name = $exists ? $class->getName() : $parameter->getName();

            $argument = $this->value($name);
        }

        return $argument;
    }

    /**
     * Returns the manipulated ServerRequest (from middleware) to an argument.
     *
     * @param  mixed                                         $argument
     * @param  \Psr\Http\Message\ServerRequestInterface|null $request
     * @return mixed
     */
    protected function request($argument, ServerRequestInterface $request = null)
    {
        $instanceof = $argument instanceof ServerRequestInterface;

        $instanceof === true && $argument = $request ?: $argument;

        return $argument;
    }

    /**
     * Returns the value of the specified argument.
     *
     * @param  string $name
     * @return mixed|null
     */
    protected function value($name)
    {
        $object = isset($this->instances[$name]) ? $this->get($name) : null;

        $exists = ! $object && $this->extra->has($name) === true;

        return $exists === true ? $this->extra->get($name) : $object;
    }
}

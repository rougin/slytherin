<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Container
 *
 * A simple container that is implemented on \Psr\Container\ContainerInterface.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
     * @var array<string, object>
     */
    public $instances = array();

    /**
     * Initializes the container instance.
     *
     * @param array<string, object>                  $instances
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(array $instances = array(), PsrContainerInterface $container = null)
    {
        $this->instances = $instances;

        if (! $container)
        {
            $container = new ReflectionContainer;
        }
 
        $this->extra = $container;
    }

    /**
     * Adds a new instance to the container.
     * NOTE: To be removed in v1.0.0. Use $this->set() instead.
     *
     * @param  string $id
     * @param  object $concrete
     * @return self
     */
    public function add($id, $concrete)
    {
        return $this->set($id, $concrete);
    }

    /**
     * Creates an alias for a specified class.
     *
     * @param  string $id
     * @param  string $original
     * @return self
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
     * @param  array<string, mixed>        $parameters
     * @return array<int, mixed>
     */
    public function arguments(\ReflectionFunctionAbstract $reflector, $parameters = array())
    {
        $items = $reflector->getParameters();

        $result = array();

        foreach ($items as $key => $item)
        {
            $argument = $this->argument($item);

            $name = (string) $item->getName();

            if (array_key_exists($name, $parameters))
            {
                $result[$key] = $parameters[$name];
            }

            if ($argument) $result[$key] = $argument;
        }

        return $result;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @param  string $id
     * @return object
     */
    public function get($id)
    {
        if (! $this->has($id))
        {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        if (isset($this->instances[$id]))
        {
            $entry = $this->instances[$id];
        }
        else
        {
            $entry = $this->resolve($id);
        }

        if (is_object($entry)) return $entry;

        $message = sprintf('Alias (%s) is not an object', $id);

        throw new Exception\ContainerException($message);
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
        /** @var class-string $id */
        $reflection = new \ReflectionClass($id);

        if (! $constructor = $reflection->getConstructor())
        {
            return $this->extra->get($id);
        }

        $result = array();

        foreach ($constructor->getParameters() as $parameter)
        {
            $argument = $this->argument($parameter);

            $result[] = $this->handle($argument, $request);
        }

        return $reflection->newInstanceArgs($result);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string $id
     * @param  object $concrete
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
        try
        {
            $argument = $parameter->getDefaultValue();
        }
        catch (\ReflectionException $exception)
        {
            $param = new Parameter($parameter);

            $class = $param->getClass();

            $name = $parameter->getName();

            if (! is_null($class)) $name = $class->getName();

            $argument = $this->value($name);
        }

        return $argument;
    }

    /**
     * Handles the manipulated ServerRequest (from middleware) to an argument.
     *
     * @param  mixed                                         $argument
     * @param  \Psr\Http\Message\ServerRequestInterface|null $request
     * @return mixed
     */
    protected function handle($argument, ServerRequestInterface $request = null)
    {
        return $argument instanceof ServerRequestInterface && $request ? $request : $argument;
    }

    /**
     * Returns the value of the specified argument.
     *
     * @param  string $name
     * @return mixed|null
     */
    protected function value($name)
    {
        $object = null;

        if (isset($this->instances[$name]))
        {
            $object = $this->get($name);
        }

        if ($object || ! $this->extra->has($name))
        {
            return $object;
        }

        // If the identifier does not exists from extra, ---
        // Try to get again from the parent container
        try
        {
            return $this->extra->get($name);
        }
        catch (NotFoundExceptionInterface $error)
        {
            return $this->get($name);
        }
        // -------------------------------------------------
    }
}
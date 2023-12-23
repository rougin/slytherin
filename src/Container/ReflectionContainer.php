<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 * Reflection Container
 *
 * A simple container utilizing PHP's Reflection classes.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer implements PsrContainer
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    protected $container = null;

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param  string $id
     * @return mixed
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @link https://petersuhm.com/recursively-resolving-dependencies-with-phps-reflection-api-part-1
     */
    public function get($id)
    {
        if (! $this->has($id))
        {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        /** @var class-string $id */
        $reflection = new \ReflectionClass((string) $id);

        if ($constructor = $reflection->getConstructor())
        {
            $arguments = $this->resolve($constructor);

            return $reflection->newInstanceArgs($arguments);
        }

        if ($this->container && $this->container->has($id))
        {
            return $this->container->get((string) $id);
        }

        return new $id;
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param  \ReflectionFunctionAbstract $reflector
     * @param  array<string, mixed>        $parameters
     * @return array<int, mixed>
     */
    public function getArguments(\ReflectionFunctionAbstract $reflector, $parameters = array())
    {
        $items = $reflector->getParameters();

        $result = array();

        foreach ($items as $key => $item)
        {
            $argument = $this->getArgument($item);

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
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        $fromContainer = false;

        if ($this->container)
        {
            $fromContainer = $this->container->has($id);
        }

        return class_exists($id) || $fromContainer;
    }

    /**
     * Returns an argument based on the given parameter.
     *
     * @param  \ReflectionParameter $parameter
     * @return mixed|null
     */
    protected function getArgument(\ReflectionParameter $parameter)
    {
        try
        {
            $argument = $parameter->getDefaultValue();
        }
        catch (\ReflectionException $exception)
        {
            // Backward compatibility for ReflectionParameter ---
            $param = new Parameter($parameter);
            // --------------------------------------------------

            $argument = null; $name = $param->getName();

            if ($this->has($name))
            {
                $argument = $this->get((string) $name);
            }
        }

        return $argument;
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param  \ReflectionFunction|\ReflectionMethod $reflection
     * @param  array<string, mixed>                  $parameters
     * @return array<int, mixed>
     */
    protected function resolve($reflection, $parameters = array())
    {
        $items = $reflection->getParameters();

        $result = array();

        foreach ($items as $key => $item)
        {
            // Backward compatibility for ReflectionParameter -------
            $param = new Parameter($item); $name = $param->getName();
            // ------------------------------------------------------

            $result[$key] = $this->getArgument($item);

            $exists = array_key_exists($name, $parameters);

            if ($exists) $result[$key] = $parameters[$name];
        }

        return $result;
    }
}

<?php

namespace Rougin\Slytherin\System;

use Psr\Container\ContainerInterface;
use Rougin\Slytherin\Container\Parameter;
use Rougin\Slytherin\Container\ReflectionContainer;

/**
 * Resolver
 *
 * Resolves the identifier from the container.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Resolver
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
     * Resolves the specified identifier to an instance.
     *
     * @param  string $id
     * @return mixed
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function resolve($id)
    {
        /** @var class-string $id */
        $reflection = new \ReflectionClass($id);

        if (! $constructor = $reflection->getConstructor())
        {
            $container = new ReflectionContainer;

            return $container->get((string) $id);
        }

        $result = array();

        foreach ($constructor->getParameters() as $parameter)
        {
            $result[] = $this->getArgument($parameter);
        }

        return $reflection->newInstanceArgs($result);
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

            if ($this->container->has($name))
            {
                $argument = $this->container->get($name);
            }
        }

        return $argument;
    }
}

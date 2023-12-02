<?php

namespace Rougin\Slytherin\System;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Parameter;
use Rougin\Slytherin\Container\ReflectionContainer;

/**
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
     * @var \Psr\Container\ContainerInterface
     */
    protected $extra;

    /**
     * @param \Psr\Container\ContainerInterface      $container
     * @param \Psr\Container\ContainerInterface|null $extra
     */
    public function __construct(ContainerInterface $container, ContainerInterface $extra = null)
    {
        $this->container = $container;

        if (! $extra)
        {
            $extra = new ReflectionContainer;
        }
 
        $this->extra = $extra;
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
            $argument = $this->getArgument($parameter);

            $result[] = $this->handle($argument, $request);
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

        if ($this->container->has($name))
        {
            $object = $this->container->get($name);
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
            return $this->container->get($name);
        }
        // -------------------------------------------------
    }
}

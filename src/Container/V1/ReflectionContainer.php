<?php

namespace Rougin\Slytherin\Container\V1;

use Rougin\Slytherin\Container\Exception\NotFoundException;
use Rougin\Slytherin\Container\Parameter;

/**
 * A simple container utilizing PHP's Reflection classes.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Finds an entry of the container by its identifier
     * and returns it.
     *
     * @param string $id
     *
     * @return mixed
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

            throw new NotFoundException(sprintf($message, $id));
        }

        if ($this->container->has($id))
        {
            return $this->container->get($id);
        }

        /** @var class-string $id */
        $reflect = new \ReflectionClass($id);

        if (! $const = $reflect->getConstructor())
        {
            return new $id;
        }

        $args = $this->resolve($const);

        return $reflect->newInstanceArgs($args);
    }

    /**
     * Returns true if the container can return an entry
     * for the given identifier.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has($id)
    {
        $inContainer = $this->container->has($id);

        return class_exists($id) || $inContainer;
    }

    /**
     * Returns an argument based on the given parameter.
     *
     * @param \ReflectionParameter $parameter
     *
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

            $argument = null;

            if ($this->has($name = $param->getName()))
            {
                $argument = $this->get($name);
            }
        }

        return $argument;
    }

    /**
     * Resolves the specified parameters from
     * a container.
     *
     * @param \ReflectionFunction|\ReflectionMethod $reflection
     *
     * @return array<integer, mixed>
     */
    protected function resolve($reflection)
    {
        $items = $reflection->getParameters();

        $result = array();

        foreach ($items as $key => $item)
        {
            $result[$key] = $this->getArgument($item);
        }

        return $result;
    }
}

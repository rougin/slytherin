<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Reflection Container
 *
 * A simple container utilizing PHP's Reflection classes.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer implements PsrContainerInterface
{
    /**
     * @link https://petersuhm.com/recursively-resolving-dependencies-with-phps-reflection-api-part-1
     *
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
            $message = sprintf('Class (%s) does not exists', $id);

            throw new Exception\NotFoundException($message);
        }

        $reflection = new \ReflectionClass($id);

        if ($constructor = $reflection->getConstructor()) {
            $arguments = $this->arguments($constructor);

            return $reflection->newInstanceArgs($arguments);
        }

        return new $id;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        return class_exists($id);
    }

    /**
     * Returns an argument based on the given parameter.
     *
     * @param  \ReflectionParameter $parameter
     * @param  string               $name
     * @return mixed|null
     */
    protected function argument(\ReflectionParameter $parameter, $name)
    {
        try {
            $argument = $parameter->getDefaultValue();
        } catch (\ReflectionException $exception) {
            $argument = $this->get($name);
        }

        return $argument;
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param  \ReflectionFunction|\ReflectionMethod $reflector
     * @param  array                                 $parameters
     * @return array
     */
    protected function arguments($reflector, $parameters = array())
    {
        $arguments = array();

        foreach ($reflector->getParameters() as $key => $parameter) {
            $class = $parameter->getClass();

            $name = $class ? $class->getName() : $parameter->getName();

            $argument = $this->argument($parameter, $name);

            $exists = array_key_exists($name, $parameters);

            $arguments[$key] = $exists ? $parameters[$name] : $argument;
        }

        return $arguments;
    }
}

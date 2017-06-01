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
        if (! $this->has($id)) {
            $message = 'Class (%s) does not exists';

            throw new Exception\NotFoundException(sprintf($message, $id));
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
     * @return mixed
     */
    protected function argument(\ReflectionParameter $parameter)
    {
        if ($parameter->isOptional() === false) {
            $class = $parameter->getClass();

            $name = $parameter->getName();

            return $this->get($class ? $class->getName() : $name);
        }

        return $parameter->getDefaultValue();
    }

    /**
     * Resolves the specified parameters from a container.
     *
     * @param  \ReflectionFunction|\ReflectionMethod $reflection
     * @param  array                                 $parameters
     * @return array
     */
    protected function arguments($reflection, $parameters = array())
    {
        $arguments = array();

        foreach ($reflection->getParameters() as $key => $parameter) {
            $argument = $this->argument($parameter);

            $name = $parameter->getName();

            $arguments[$key] = $argument ?: $parameters[$name];
        }

        return $arguments;
    }
}

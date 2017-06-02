<?php

namespace Rougin\Slytherin\Container;

/**
 * Reflection Container
 *
 * A simple container utilizing PHP's Reflection classes.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ReflectionContainer implements \Psr\Container\ContainerInterface
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
            $name = $parameter->getName();

            try {
                $argument = $parameter->getDefaultValue();
            } catch (\ReflectionException $e) {
                $class = $parameter->getClass();

                $argument = $this->get($class ? $class->getName() : $name);
            }

            $arguments[$key] = $argument ?: $parameters[$name];
        }

        return $arguments;
    }
}

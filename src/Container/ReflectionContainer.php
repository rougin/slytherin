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
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(PsrContainerInterface $container)
    {
        $this->container = $container;
    }

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
     * Resolves the specified function based on its callback and parameters.
     *
     * @param  array $function
     * @return mixed
     */
    public function resolve(array $function)
    {
        list($callback, $parameters) = $function;

        if (is_array($callback) && ! is_object($callback)) {
            list($name, $method) = $callback;

            $callback = array($this->get($name), $method);

            $reflector = new \ReflectionMethod($callback[0], $method);
        } else {
            $reflector = new \ReflectionFunction($callback);
        }

        $arguments = $this->arguments($reflector, $parameters);

        return call_user_func_array($callback, $arguments);
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
            if ($parameter->getClass()) {
                $name = $parameter->getClass()->getName();

                $exists = $this->container->has($name);

                $container = ($exists) ? $this->container : $this;

                return $container->get($name);
            }

            return null;
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
            $name = $parameter->getName();

            $argument = $this->argument($parameter);

            $arguments[$key] = $argument ?: $parameters[$name];
        }

        return $arguments;
    }
}

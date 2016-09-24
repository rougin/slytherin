<?php

namespace Rougin\Slytherin\Application\Traits;

use Interop\Container\ContainerInterface;

/**
 * Resolve Class Trait
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait ResolveClassTrait
{
    /**
     * Resolves the result based from the dispatched route.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  array|string                          $function
     * @return mixed
     */
    private function resolveClass(ContainerInterface $container, $function)
    {
        if (is_string($function)) {
            return $function;
        }

        list($class, $parameters) = $function;

        if (is_callable($class) && is_object($class)) {
            return call_user_func($class, $parameters);
        }

        list($className, $method) = $class;

        $result = $this->resolve($container, $className);

        return call_user_func_array([ $result, $method ], $parameters);
    }

    /**
     * Resolves the dependencies on the specified class.
     *
     * @link   http://goo.gl/wN8Vaz
     * @param  \Interop\Container\ContainerInterface $container
     * @param  string                                $className
     * @return mixed
     */
    private function resolve(ContainerInterface $container, $className)
    {
        // Reflect on the $className
        $reflectionClass = new \ReflectionClass($className);

        // If there is no constructor, there is no dependencies, which means
        // that our job is done.
        if (! $constructor = $reflectionClass->getConstructor()) {
            return new $className;
        }

        // Fetch the parameters from the constructor
        // (collection of ReflectionParameter instances)
        $parameters = $constructor->getParameters();

        // This is were we store the dependencies
        $arguments = $this->setArguments($container, $parameters);

        // Return the reflected class, instantiated with all its dependencies
        // (this happens once for all the nested dependencies).
        return $reflectionClass->newInstanceArgs($arguments);
    }

    /**
     * Sets the arguments from the specified class.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  array                                 $parameters
     * @return array
     */
    private function setArguments(ContainerInterface $container, array $parameters)
    {
        $arguments = [];

        // Loop over the constructor parameters
        $callback = function ($parameter) use ($container, &$arguments) {
            if ($parameter->isOptional()) {
                return array_push($arguments, $parameter->getDefaultValue());
            }

            $class = $parameter->getClass()->getName();

            if ($container->has($class)) {
                return array_push($arguments, $container->get($class));
            }

            // This is where 'the magic happens'. We resolve each of the
            // dependencies, by recursively calling the resolve() method.
            // At one point, we will reach the bottom of the nested
            // dependencies we need in order to instantiate the class.
            array_push($arguments, $this->resolve($container, $class));
        };

        array_walk($parameters, $callback);

        return $arguments;
    }
}

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
     * @param  array $function
     * @return mixed
     */
    private function resolveClass(ContainerInterface $container, $function)
    {
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
        // This is were we store the dependencies
        $parameters = [];

        // Reflect on the $className
        $reflectionClass = new \ReflectionClass($className);

        // If there is no constructor, there is no dependencies, which means
        // that our job is done.
        if (! $constructor = $reflectionClass->getConstructor()) {
            return new $className;
        } else {
            // Fetch the arguments from the constructor
            // (collection of ReflectionParameter instances)
            $oldParameters = $constructor->getParameters();
        }

        // Loop over the constructor arguments
        $callback = function ($parameter) use ($container, &$parameters) {
            if ($parameter->isOptional()) {
                return array_push($parameters, $parameter->getDefaultValue());
            }

            $class = $parameter->getClass()->getName();

            if ($container->has($class)) {
                return array_push($parameters, $container->get($class));
            }

            // This is where 'the magic happens'. We resolve each of the
            // dependencies, by recursively calling the resolve() method.
            // At one point, we will reach the bottom of the nested
            // dependencies we need in order to instantiate the class.
            array_push($parameters, $this->resolve($container, $class));
        };

        array_walk($oldParameters, $callback);

        // Return the reflected class, instantiated with all its dependencies
        // (this happens once for all the nested dependencies).
        return $reflectionClass->newInstanceArgs($parameters);
    }
}

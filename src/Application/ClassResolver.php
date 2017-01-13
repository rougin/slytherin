<?php

namespace Rougin\Slytherin\Application;

use Interop\Container\ContainerInterface;

/**
 * Class Resolver
 *
 * Resolves the included classes based from the defined container.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ClassResolver
{
    /**
     * Parses the specified arguments.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  \ReflectionParameter                  $parameter
     * @param  array                                 &$arguments
     * @return void
     */
    private function parseParameters(ContainerInterface $container, $parameter, array &$arguments)
    {
        if ($parameter->isOptional()) {
            return array_push($arguments, $parameter->getDefaultValue());
        }

        $class = $parameter->getClass()->getName();

        if ($container->has($class)) {
            return array_push($arguments, $container->get($class));
        }

        array_push($arguments, $this->resolve($container, $class));
    }

    /**
     * Resolves the result based from the dispatched route.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  array|string                          $function
     * @return mixed
     */
    public function resolveClass(ContainerInterface $container, $function)
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

        return call_user_func_array(array($result, $method), $parameters);
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
        $reflectionClass = new \ReflectionClass($className);

        if (! $constructor = $reflectionClass->getConstructor()) {
            return new $className;
        }

        $parameters = $constructor->getParameters();
        $arguments  = $this->setArguments($container, $parameters);

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
        $arguments = array();

        foreach ($parameters as $parameter) {
            $this->parseParameters($container, $parameter, $arguments);
        }

        return $arguments;
    }
}

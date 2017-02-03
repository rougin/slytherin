<?php

namespace Rougin\Slytherin\Application;

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
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(\Interop\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Resolves the result based from the dispatched route.
     *
     * @param  array|string $function
     * @return mixed
     */
    public function resolveClass($function)
    {
        $result = $function;

        if (is_array($function)) {
            list($class, $parameters) = $function;

            if (is_callable($class) && is_object($class)) {
                return call_user_func_array($class, $parameters);
            }

            list($className, $method) = $class;

            $result = $this->resolve($className);
            $result = call_user_func_array(array($result, $method), $parameters);
        }

        return $result;
    }

    /**
     * Parses the specified arguments.
     *
     * @param  \ReflectionParameter $parameter
     * @param  array                &$arguments
     * @return void
     */
    private function parseParameters($parameter, array &$arguments)
    {
        if ($parameter->isOptional()) {
            return array_push($arguments, $parameter->getDefaultValue());
        }

        $class = $parameter->getClass()->getName();

        if ($this->container->has($class)) {
            return array_push($arguments, $this->container->get($class));
        }

        array_push($arguments, $this->resolve($class));
    }

    /**
     * Resolves the dependencies on the specified class.
     *
     * @link   http://goo.gl/wN8Vaz
     * @param  string $className
     * @return mixed
     */
    private function resolve($className)
    {
        $reflectionClass = new \ReflectionClass($className);

        if (! $constructor = $reflectionClass->getConstructor()) {
            return new $className;
        }

        $parameters = $constructor->getParameters();
        $arguments  = $this->setArguments($parameters);

        return $reflectionClass->newInstanceArgs($arguments);
    }

    /**
     * Sets the arguments from the specified class.
     *
     * @param  array $parameters
     * @return array
     */
    private function setArguments(array $parameters)
    {
        $arguments = array();

        foreach ($parameters as $parameter) {
            $this->parseParameters($parameter, $arguments);
        }

        return $arguments;
    }
}

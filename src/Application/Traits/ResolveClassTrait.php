<?php

namespace Rougin\Slytherin\Application\Traits;

use ReflectionClass;
use ReflectionParameter;

use Interop\Container\ContainerInterface;

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
     * @param  string $className
     * @return mixed
     */
    private function resolve(ContainerInterface $container, $className)
    {
        // This is were we store the dependencies
        $newParameters = [];

        // Reflect on the $className
        $reflectionClass = new ReflectionClass($className);

        // If there is no constructor, there is no dependencies, which means
        // that our job is done.
        if (! $constructor = $reflectionClass->getConstructor()) {
            return new $className;
        } else {
            // Fetch the arguments from the constructor
            // (collection of ReflectionParameter instances)
            $parameters = $constructor->getParameters();
        }

        // Loop over the constructor arguments
        foreach ($parameters as $parameter) {
            if ($parameter->isOptional()) {
                $this->setParameter($parameter, $newParameters);

                continue;
            }

            $class = $parameter->getClass()->getName();

            if ($container->has($class)) {
                array_push($newParameters, $container->get($class));

                continue;
            }

            // This is where 'the magic happens'. We resolve each of the
            // dependencies, by recursively calling the resolve() method.
            // At one point, we will reach the bottom of the nested
            // dependencies we need in order to instantiate the class.
            array_push($newParameters, $this->resolve($container, $class));
        }

        // Return the reflected class, instantiated with all its dependencies
        // (this happens once for all the nested dependencies).
        return $reflectionClass->newInstanceArgs($newParameters);
    }

    /**
     * Sets the parameter from the ReflectionParameter class.
     *
     * @param  \ReflectionParameter $parameter
     * @param  array &$parameters
     * @return void
     */
    private function setParameter(ReflectionParameter $parameter, &$parameters = [])
    {
        $newParameter = null;

        if ($parameter->isDefaultValueAvailable()) {
            $newParameter = $parameter->getDefaultValue();
        }

        array_push($parameters, $newParameter);
    }
}

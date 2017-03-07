<?php

namespace Rougin\Slytherin\Application;

/**
 * Class Resolver
 *
 * Solves the included classes based from the defined container.
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
     * Resolves the dependencies on the specified class.
     *
     * @link   http://goo.gl/wN8Vaz
     * @param  string $class
     * @return mixed
     */
    public function resolve($class)
    {
        $reflection = new \ReflectionClass($class);

        if ($constructor = $reflection->getConstructor()) {
            $parameters = $constructor->getParameters();
            $arguments  = $this->setArguments($parameters);

            return $reflection->newInstanceArgs($arguments);
        }

        return new $class;
    }

    /**
     * Parses the specified arguments.
     *
     * @param  \ReflectionParameter $parameter
     * @param  array                &$arguments
     * @return void
     */
    protected function parseParameters($parameter, array &$arguments)
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
     * Sets the arguments from the specified class.
     *
     * @param  array $parameters
     * @return array
     */
    protected function setArguments(array $parameters)
    {
        $arguments = array();

        foreach ($parameters as $parameter) {
            $this->parseParameters($parameter, $arguments);
        }

        return $arguments;
    }
}

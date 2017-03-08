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
     * Parses the specified parameters.
     *
     * @param  \ReflectionParameter $parameter
     * @return mixed
     */
    protected function parseParameter(\ReflectionParameter $parameter)
    {
        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        $className = $parameter->getClass()->getName();

        if ($this->container->has($className)) {
            return $this->container->get($className);
        }

        return $this->resolve($className);
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

        foreach ($parameters as $item) {
            $parameter = $this->parseParameter($item);

            array_push($arguments, $parameter);
        }

        return $arguments;
    }
}

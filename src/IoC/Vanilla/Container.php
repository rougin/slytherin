<?php

namespace Rougin\Slytherin\IoC\Vanilla;

use ReflectionClass;

use Rougin\Slytherin\IoC\BaseContainer;
use Rougin\Slytherin\IoC\ContainerInterface;

/**
 * Container
 *
 * A container that extends on Rougin\Slytherin\IoC\BaseContainer.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container extends BaseContainer implements ContainerInterface
{
    /**
     * Adds a new instance to the container.
     * 
     * @param string $id
     * @param mixed  $concrete
     */
    public function add($id, $concrete = null)
    {
        if ($concrete) {
            $this->instances[$id] = $concrete;

            return $this;
        }

        $this->instances[$id] = $this->resolve($id);

        return $this;
    }

    /**
     * Creates an alias for a specified class.
     * 
     * @param string $alias
     * @param string $original
     */
    public function alias($alias, $original)
    {
        $this->instances[$alias] = $this->get($original);

        return $this;
    }

    /**
     * Resolves the dependencies on the specified class.
     *
     * @link   http://goo.gl/wN8Vaz
     * @param  string $class
     * @return mixed
     */
    private function resolve($class)
    {
        // Reflect on the $class
        $reflectionClass = new ReflectionClass($class);

        // Fetch the constructor (instance of ReflectionMethod)
        $constructor = $reflectionClass->getConstructor();

        // If there is no constructor, there is no dependencies, which means
        // that our job is done.
        if ( ! $constructor) {
            return new $class;
        }

        // Fetch the arguments from the constructor
        // (collection of ReflectionParameter instances)
        $parameters = $constructor->getParameters();

        // This is were we store the dependencies
        $newParameters = [];

        // Loop over the constructor arguments
        foreach ($parameters as $param) {
            if ($param->isOptional()) {
                $newParameters[] = $param->isDefaultValueAvailable()
                    ? $param->getDefaultValue()
                    : null;

                continue;
            }

            $class = $param->getClass()->getName();

            if ($this->has($class)) {
                $newParameters[] = $this->get($class);

                continue;
            }

            // This is where 'the magic happens'. We resolve each of the
            // dependencies, by recursively calling the resolve() method.
            // At one point, we will reach the bottom of the nested
            // dependencies we need in order to instantiate the class.
            $newParameters[] = $this->resolve($class);
        }

        // Return the reflected class, instantiated with all its dependencies
        // (this happens once for all the nested dependencies).
        return $reflectionClass->newInstanceArgs($newParameters);
    }
}

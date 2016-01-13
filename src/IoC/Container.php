<?php

namespace Rougin\Slytherin\IoC;

use ReflectionClass;
use Rougin\Slytherin\IoC\Exception\NotFoundException;

/**
 * Container
 *
 * A simple implementation of a container that is based on
 * Rougin\Slytherin\IoC\ContainerInterface.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $instances = [];

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
     * @param string $original
     * @param string $alias
     */
    public function alias($original, $alias)
    {
        $this->instances[$original] = $this->instances[$alias];

        return $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (! $this->has($id)) {
            $message = 'Alias (%s) is not being managed by the container';

            throw new NotFoundException(sprintf($message, $id));
        }

        return $this->instances[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param  string $id Identifier of the entry to look for.
     * @return boolean
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
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

        if ($reflectionClass->isInterface()) {
            return $this->get($class);
        }

        // If there is no constructor, there is no dependencies, which means
        // that our job is done.
        if ( ! $constructor) {
            return new $class;
        }

        // Fetch the arguments from the constructor
        // (collection of ReflectionParameter instances)
        $parameters = $constructor->getParameters();

        // If there is a constructor, but no dependencies, our job is done.
        if (count($parameters) === 0) {
            return new $class;
        }

        // This is were we store the dependencies
        $newParameters = [];

        // Loop over the constructor arguments
        foreach ($parameters as $param) {
            // Here we should perform a bunch of checks, such as: isArray(),
            // isCallable(), isDefaultValueAvailable() isOptional() etc.

            if ($param->isOptional()) {
                $newParameters[] = $param->getDefaultValue();

                continue;
            }

            // For now, we just check to see if the argument is a class, so we
            // can instantiate it, otherwise we just pass null.
            if (is_null($param->getClass())) {
                $newParameters[] = null;

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

        // Return the reflected class, instantiated with all its
        // dependencies (this happens once for all the
        // nested dependencies).
        return $reflectionClass->newInstanceArgs($newParameters);
    }
}

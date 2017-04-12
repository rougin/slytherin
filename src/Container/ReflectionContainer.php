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
     * @var array
     */
    protected $arguments = array();

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
            foreach ($constructor->getParameters() as $parameter) {
                array_push($this->arguments, $this->argument($parameter));
            }

            return $reflection->newInstanceArgs($this->arguments);
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
     * Returns an argument based on the given parameter.
     *
     * @param  \ReflectionParameter $parameter
     * @return mixed
     */
    protected function argument(\ReflectionParameter $parameter)
    {
        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        $name = $parameter->getClass()->getName();

        $exists = $this->container->has($name);

        $container = ($exists) ? $this->container : $this;

        return $container->get($name);
    }
}
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
class ReflectionContainer implements DelegateInterface, PsrContainerInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(PsrContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Delegate a container to be checked for services.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return self
     */
    public function delegate(PsrContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
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

        return $this->resolve($id);
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

        if ($this->container && $this->container->has($name)) {
            return $this->container->get($name);
        }

        return $this->get($name);
    }

    /**
     * @link https://petersuhm.com/recursively-resolving-dependencies-with-phps-reflection-api-part-1
     *
     * Resolves dependencies recursively using PHP's Reflection API.
     *
     * @param  string $id
     * @return mixed
     */
    protected function resolve($id)
    {
        $reflection = new \ReflectionClass($id);

        if ($constructor = $reflection->getConstructor()) {
            $arguments = array();

            foreach ($constructor->getParameters() as $parameter) {
                array_push($arguments, $this->argument($parameter));
            }

            return $reflection->newInstanceArgs($arguments);
        }

        return new $id;
    }
}

<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * Container
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container implements \Psr\Container\ContainerInterface, \Rougin\Slytherin\Container\DelegateInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $delegate;

    /**
     * @var array
     */
    protected $instances = array();

    /**
     * Delegate a container to be checked for services.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return self
     */
    public function delegate(PsrContainerInterface $container)
    {
        $this->delegate = $container;

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
        return $this->instances[$id];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }
}

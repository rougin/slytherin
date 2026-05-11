<?php

namespace Rougin\Slytherin\Component\V2;

/**
 * Contains all the required components for Slytherin.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Collection
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * Returns the specified component.
     *
     * @param string $id
     *
     * @return object
     */
    public function get(string $id)
    {
        /** @var object */
        return $this->container->get($id);
    }

    /**
     * Checks if a specified component exists.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    /**
     * Sets an instance to the collection.
     *
     * @param string $id
     * @param mixed  $concrete
     *
     * @return self
     */
    public function set($id, $concrete = null)
    {
        $this->container->set($id, $concrete);

        return $this;
    }
}

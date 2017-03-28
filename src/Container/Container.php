<?php

namespace Rougin\Slytherin\Container;

/**
 * Container
 *
 * A simple container that is implemented on \Interop\Container\ContainerInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    public $instances = array();

    /**
     * Adds a new instance to the container.
     * NOTE: To be removed in v1.0.0
     *
     * @param  string     $alias
     * @param  mixed|null $concrete
     * @return self
     */
    public function add($alias, $concrete = null)
    {
        return $this->set($alias, $concrete);
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
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param  string $alias Identifier of the entry to look for.
     * @return mixed
     */
    public function get($alias)
    {
        if (! $this->has($alias)) {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $alias));
        }

        return $this->instances[$alias];
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $alias
     * @return boolean
     */
    public function has($alias)
    {
        return isset($this->instances[$alias]);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string     $alias
     * @param  mixed|null $concrete
     * @return self
     */
    public function set($alias, $concrete = null)
    {
        $this->instances[$alias] = $concrete;

        return $this;
    }
}

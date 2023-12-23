<?php

namespace Rougin\Slytherin\Container;

/**
 * Container
 *
 * A simple container that is implemented on PSR-11.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array<string, mixed>
     */
    protected $items = array();

    /**
     * Initializes the container instance.
     *
     * @param array<string, mixed> $items
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    /**
     * Adds a new instance to the container.
     * NOTE: To be removed in v1.0.0. Use "set" instead.
     *
     * @param  string $id
     * @param  mixed  $concrete
     * @return self
     */
    public function add($id, $concrete)
    {
        return $this->set($id, $concrete);
    }

    /**
     * Creates an alias for a specified class.
     *
     * @param  string $id
     * @param  string $original
     * @return self
     */
    public function alias($id, $original)
    {
        $this->items[$id] = $this->get($original);

        return $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param  string $id
     * @return mixed
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function get($id)
    {
        if (! $this->has($id))
        {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        $entry = $this->items[(string) $id];

        if (is_object($entry)) return $entry;

        $message = sprintf('Alias (%s) is not an object', $id);

        throw new Exception\ContainerException($message);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        return isset($this->items[$id]);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string $id
     * @param  mixed  $concrete
     * @return self
     */
    public function set($id, $concrete)
    {
        $this->items[$id] = $concrete;

        return $this;
    }
}

<?php

namespace Rougin\Slytherin\Container;

Interop::register('Container');

/**
 * @package Slytherin
 *
 * @method mixed                                          get(string $id)
 * @method boolean                                        has(string $id)
 * @method \Rougin\Slytherin\Container\ContainerInterface set(string $id, $concrete)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Container extends PsrContainer implements ContainerInterface
{
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
     * @deprecated since ~0.9, use "set" instead.
     *
     * Adds a new instance to the container.
     *
     * @param string $id
     * @param mixed  $concrete
     *
     * @return self
     */
    public function add($id, $concrete)
    {
        $this->set($id, $concrete);

        return $this;
    }

    /**
     * Creates an alias for a specified class.
     *
     * @param string $id
     * @param string $original
     *
     * @return self
     */
    public function alias($id, $original)
    {
        $this->items[$id] = $this->get($original);

        return $this;
    }
}

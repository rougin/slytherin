<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Container
 *
 * A simple container that is implemented on \Psr\Container\ContainerInterface.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Container implements ContainerInterface, DelegateInterface
{
    /**
     * NOTE: To be removed in v1.0.0. Use "protected" visibility instead.
     *
     * @var array
     */
    public $instances = array();

    /**
     * @var array
     */
    protected $delegates = array();

    /**
     * @param array $instances
     */
    public function __construct(array $instances = array())
    {
        $this->instances = $instances;
    }

    /**
     * Adds a new instance to the container.
     * NOTE: To be removed in v1.0.0. Use $this->set() instead.
     *
     * @param  string     $id
     * @param  mixed|null $concrete
     * @return self
     */
    public function add($id, $concrete = null)
    {
        return $this->set($id, $concrete);
    }

    /**
     * Creates an alias for a specified class.
     *
     * @param string $id
     * @param string $original
     */
    public function alias($id, $original)
    {
        $this->instances[$id] = $this->get($original);

        return $this;
    }

    /**
     * Delegate a container to be checked for services.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return self
     */
    public function delegate(PsrContainerInterface $container)
    {
        if ($container instanceof DelegateInterface) {
            $container->delegate($this);
        }

        array_push($this->delegates, $container);

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
        $entry = ($this->has($id)) ? $this->instances[$id] : $this->resolve($id);

        if (! is_callable($entry) && ! is_object($entry)) {
            $message = 'Alias (%s) is not a callable or an object';

            throw new Exception\ContainerException(sprintf($message, $id));
        }

        return $entry;
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

    /**
     * Sets a new instance to the container.
     *
     * @param  string     $id
     * @param  mixed|null $concrete
     * @return self
     */
    public function set($id, $concrete = null)
    {
        $this->instances[$id] = $concrete;

        return $this;
    }

    /**
     * Resolves dependencies based on the defined delegates.
     *
     * @param  string $id
     * @return mixed
     */
    protected function resolve($id)
    {
        $entry = null;

        $callback = function ($item) use ($id, &$entry) {
            ! $item->has($id) || $entry = $item->get($id);
        };

        array_walk($this->delegates, $callback);

        if ($entry == null) {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        return $entry;
    }
}

<?php

namespace Rougin\Slytherin\Container;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\System\Resolver;

/**
 * Container
 *
 * A simple container that is implemented on \Psr\Container\ContainerInterface.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $extra;

    /**
     * NOTE: To be removed in v1.0.0. Use "protected" visibility instead.
     *
     * @var array<string, object>
     */
    public $instances = array();

    /**
     * Initializes the container instance.
     *
     * @param array<string, object>                  $instances
     * @param \Psr\Container\ContainerInterface|null $container
     */
    public function __construct(array $instances = array(), PsrContainerInterface $container = null)
    {
        $this->instances = $instances;

        if (! $container)
        {
            $container = new ReflectionContainer;
        }
 
        $this->extra = $container;
    }

    /**
     * Adds a new instance to the container.
     * NOTE: To be removed in v1.0.0. Use "set" instead.
     *
     * @param  string $id
     * @param  object $concrete
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
        $this->instances[$id] = $this->get($original);

        return $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @param  string $id
     * @return object
     */
    public function get($id)
    {
        if (! $this->has($id))
        {
            $message = 'Alias (%s) is not being managed by the container';

            throw new Exception\NotFoundException(sprintf($message, $id));
        }

        if (isset($this->instances[$id]))
        {
            $entry = $this->instances[$id];
        }
        else
        {
            $resolver = new Resolver($this);

            $entry = $resolver->resolve($id);
        }

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
        return isset($this->instances[$id]) || $this->extra->has($id);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string $id
     * @param  object $concrete
     * @return self
     */
    public function set($id, $concrete)
    {
        $this->instances[$id] = $concrete;

        return $this;
    }
}

<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\System\Errors\ComponentNotFound;

/**
 * Collects all defined components into a Collection.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Collector
{
    /**
     * @var \Rougin\Slytherin\Component\ComponentInterface[]
     */
    protected $items = array();

    /**
     * @param \Rougin\Slytherin\Component\ComponentInterface[]|string[] $items
     */
    public function __construct(array $items = array())
    {
        foreach ($items as $item)
        {
            if (is_string($item))
            {
                $item = new $item;
            }

            if (! $item instanceof ComponentInterface)
            {
                throw new ComponentNotFound($item);
            }

            $this->items[] = $item;
        }
    }

    /**
     * Creates a new collection.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     *
     * @return \Rougin\Slytherin\Component\Collection
     */
    public function make(ContainerInterface $container)
    {
        $collection = new Collection;

        $collection->setContainer($container);

        foreach ($this->items as $item)
        {
            $item->register($collection);
        }

        return $collection;
    }

    /**
     * Initializes the specified components.
     *
     * @param \Rougin\Slytherin\Component\ComponentInterface[]|string[] $components
     * @param \Rougin\Slytherin\Container\ContainerInterface|null       $container
     *
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(array $components, $container = null)
    {
        $self = new Collector($components);

        $container = $container ? $container : new Container;

        return $self->make($container);
    }
}

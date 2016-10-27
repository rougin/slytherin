<?php

namespace Rougin\Slytherin\Component;

use Interop\Container\ContainerInterface;

/**
 * Component Collector
 *
 * Collects all components into Collection.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Collector
{
    /**
     * Collects the specified components.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @param  array                                 $components
     * @param  array|null                            $globals
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(ContainerInterface $container, $components = [], &$globals = null)
    {
        $collection = new Collection;

        $callback = function ($component) use (&$collection, &$container) {
            $instance = self::prepareComponent($collection, $component);

            $instance->set($container);
        };

        array_walk($components, $callback);

        $collection->setContainer($container);

        // NOTE: To be removed in v1.0.0
        if ($globals) {
            $globals['container'] = $container;
        }

        return $collection;
    }

    /**
     * Prepares the component and sets it to the collection.
     *
     * @param  \Rougin\Slytherin\Component\Collection &$collection
     * @param  string                                 $component
     * @return \Rougin\Slytherin\Component\ComponentInterface
     */
    protected static function prepareComponent(Collection &$collection, $component)
    {
        $instance = new $component;

        $type = $instance->getType();

        if (! empty($instance->getType())) {
            $method     = 'set' . ucfirst($instance->getType());
            $parameters = [ $instance->get() ];

            if ($instance->getType() == 'http') {
                $parameters = $instance->get();
            }

            call_user_func_array([ $collection, $method ], $parameters);
        }

        return $instance;
    }
}

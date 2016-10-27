<?php

namespace Rougin\Slytherin\Component;

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
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(\Interop\Container\ContainerInterface $container, $components = [])
    {
        $collection = new Collection;

        $callback = function ($component) use (&$collection, &$container) {
            $instance = self::prepareComponent($collection, $component);

            $instance->set($container);
        };

        array_walk($components, $callback);

        $collection->setContainer($container);

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

        if (! empty($type)) {
            $parameters = [ $instance->get() ];

            if ($type == 'http') {
                $parameters = $instance->get();
            }

            call_user_func_array([ $collection, 'set' . ucfirst($type) ], $parameters);
        }

        return $instance;
    }
}

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
     * @param  array|null                            $globals
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(\Interop\Container\ContainerInterface $container, array $components = array(), &$globals = null)
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

        if (! empty($type)) {
            $parameters = array($instance->get());

            if ($type == 'http') {
                $parameters = $instance->get();
            }

            call_user_func_array(array($collection, 'set' . ucfirst($type)), $parameters);
        }

        return $instance;
    }
}

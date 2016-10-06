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
            $component = new $component;

            $type = $component->getType();

            if (! empty($type)) {
                $method     = 'set' . ucfirst($type);
                $parameters = ($type == 'http') ? $component->get() : [ $component->get() ];

                call_user_func_array([ $collection, $method ], $parameters);
            }

            $component->set($container);
        };

        array_walk($components, $callback);

        $collection->setContainer($container);

        // NOTE: To be removed in v1.0.0
        if ($globals) {
            $globals['container'] = $container;
        }

        return $collection;
    }
}

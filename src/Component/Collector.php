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

            if (! empty($component->getType())) {
                $method = 'set' . ucfirst($component->getType());

                if ($component->getType() == 'http') {
                    list($request, $response) = $component->get();

                    $collection->$method($request, $response);
                } else {
                    $collection->$method($component->get());
                }
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

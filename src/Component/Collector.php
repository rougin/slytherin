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
     * @param  array|null                            $globals
     * @param  array                                 $components
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(ContainerInterface $container, $components = [], &$globals = null)
    {
        $collection = new Collection;

        foreach ($components as $component) {
            $component = new $component;

            switch ($component->getType()) {
                case 'dispatcher':
                    $collection->setDispatcher($component->get());

                    break;
                case 'debugger':
                    $collection->setDebugger($component->get());

                    break;
                case 'http':
                    list($request, $response) = $component->get();

                    $collection->setHttp($request, $response);

                    break;
                case 'middleware':
                    $collection->setMiddleware($component->get());

                    break;
            }

            $component->set($container);
        }

        $collection->setContainer($container);

        if ($globals) {
            $globals['container'] = $container;
        }

        return $collection;
    }
}

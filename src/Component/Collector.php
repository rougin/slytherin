<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\IoC\ContainerInterface;

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
     * @param  \Rougin\Slytherin\IoC\ContainerInterface $container
     * @param  array|null $globals
     * @param  array      $components
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(ContainerInterface $container, $components = [], &$globals = null)
    {
        $collection = new Collection;

        foreach ($components as $component) {
            $component = new $component;
            $componentContainer = $component->needsContainer() ? $container : null;

            switch ($component->getType()) {
                case 'dispatcher':
                    $collection->setDispatcher($component->get());

                    break;
                case 'debugger':
                    $collection->setDebugger($component->get());

                    break;
                case 'http':
                    list($request, $response) = $component->get();

                    $container->add('Psr\Http\Message\ServerRequestInterface', $request);
                    $container->add('Psr\Http\Message\ResponseInterface', $response);

                    $collection->setHttp($request, $response);

                    break;
                case 'middleware':
                    $collection->setMiddleware($component->get());

                    break;
            }

            if ( ! $component->getType()) {
                $componentName = (string) $component;
                $component = $component->get($componentContainer);

                if (is_array($component)) {
                    foreach ($component as $key => $value) {
                        $container->add($key, $value);
                    }
                } else {
                    $container->add($componentName, $component);
                }
            }
        }

        $collection->setContainer($container);

        if ($globals) {
            $globals['container'] = $container;
        }

        return $collection;
    }
}

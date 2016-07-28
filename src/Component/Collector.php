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
                self::set($component, $container);
            }
        }

        $collection->setContainer($container);

        if ($globals) {
            $globals['container'] = $container;
        }

        return $collection;
    }

    /**
     * Sets the component and add it to the container.
     * 
     * @param  \Rougin\Slytherin\Component\ComponentInterface $component
     * @param  \Rougin\Slytherin\IoC\ContainerInterface &$container
     * @return void
     */
    private static function set(ComponentInterface $component, ContainerInterface & $container)
    {
        $componentContainer = $component->needsContainer() ? $container : null;
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

<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Component Collector
 *
 * Collects all components into Collection.
 * NOTE: To be removed in v1.0.0. Use "Integration" instead.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Collector
{
    /**
     * Collects the specified components.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  array                                          $components
     * @param  array|null                                     $globals
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(ContainerInterface $container, array $components = array(), &$globals = null)
    {
        $configuration = new \Rougin\Slytherin\Integration\Configuration;

        $collection = new Collection;

        foreach ($components as $component) {
            $instance = self::prepare($collection, $component);

            $container = $instance->define($container, $configuration);
        }

        $collection->setContainer($container);

        // NOTE: To be removed in v1.0.0. Use Application::container instead.
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
     * @return \Rougin\Slytherin\Integration\IntegrationInterface
     */
    protected static function prepare(Collection &$collection, $component)
    {
        $instance = new $component;

        $type = $instance->type();

        if (! empty($type)) {
            $parameters = ($type == 'http') ? $instance->get() : array($instance->get());

            call_user_func_array(array($collection, 'set' . ucfirst($type)), $parameters);
        }

        return $instance;
    }
}

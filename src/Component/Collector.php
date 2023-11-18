<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;

/**
 * Component Collector
 *
 * Collects all components into Collection.
 * NOTE: To be removed in v1.0.0. Use "Integration" instead.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Collector
{
    /**
     * Collects the specified components.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  string[]                                       $components
     * @param  array<string, mixed>|null                      $globals
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(ContainerInterface $container, array $components = array(), &$globals = null)
    {
        $configuration = new Configuration;

        $collection = new Collection;

        foreach ($components as $component)
        {
            $instance = self::prepare($collection, $component);

            $container = $instance->define($container, $configuration);
        }

        $collection->setContainer($container);

        // NOTE: To be removed in v1.0.0. Use Application::container instead. ---
        if ($globals)
        {
            $globals['container'] = $container;
        }
        // ----------------------------------------------------------------------

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

        if (empty($type)) return $instance;

        $arguments = array($instance->get());

        if ($type === 'http')
        {
            $arguments = $instance->get();
        }

        $class = array($collection, 'set' . ucfirst($type));

        call_user_func_array($class, $arguments);

        return $instance;
    }
}
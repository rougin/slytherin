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
        /** @var class-string $component */
        $instance = new $component;

        /** @var callable */
        $class = array($instance, 'type');
        $type = call_user_func($class);

        if (empty($type))
        {
            /** @var \Rougin\Slytherin\Integration\IntegrationInterface */
            return $instance;
        }

        /** @var callable */
        $class = array($instance, 'get');
        $args = call_user_func($class);

        if ($type !== 'http') $args = array($args);

        /** @var callable */
        $class = array($collection, 'set' . ucfirst($type));

        call_user_func_array($class, $args);

        /** @var \Rougin\Slytherin\Integration\IntegrationInterface */
        return $instance;
    }
}
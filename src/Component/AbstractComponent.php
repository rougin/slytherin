<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Component Abstract
 *
 * Methods used for integrating a component to Slytherin.
 * NOTE: To be removed in v1.0.0. Use "Integration" instead.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class AbstractComponent implements ComponentInterface
{
    /**
     * The type of component can be the following:
     * dispatcher, error_handler, http, middleware
     *
     * @var string
     */
    protected $type = '';

    /**
     * Returns the type of the component.
     *
     * @return string
     */
    public function type()
    {
        // Converts the string from "snake_case" to "camelCase"
        $words = ucwords(str_replace('_', ' ', $this->type));

        return lcfirst(str_replace(' ', '', $words));
    }

    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        $this->set($container);

        return $container;
    }

    /**
     * Sets the component. Has also an option to add it to the container.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return void
     */
    public function set(\Psr\Container\ContainerInterface &$container)
    {
    }
}

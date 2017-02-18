<?php

namespace Rougin\Slytherin\Component;

/**
 * Component Abstract
 *
 * Methods used for integrating a component to Slytherin.
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the component. Has also an option to add it to the container.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @return void
     */
    public function set(\Interop\Container\ContainerInterface &$container)
    {
    }
}

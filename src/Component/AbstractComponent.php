<?php

namespace Rougin\Slytherin\Component;

use Interop\Container\ContainerInterface;

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
     * Name of the class to be added in the container.
     * 
     * @var string
     */
    protected $className = '';

    /**
     * Checks if the said component needs a container.
     * 
     * @var boolean
     */
    protected $container = false;

    /**
     * Type of the component:
     * dispatcher, debugger, http, middleware
     * 
     * @var string
     */
    protected $type;

    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    abstract public function get();

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
     * Sets the component and add it to the container of your choice.
     * 
     * @param  \Interop\Container\ContainerInterface $container
     * @return void
     */
    public function set(ContainerInterface &$container)
    {
        return;
    }
}

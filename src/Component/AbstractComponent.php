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
abstract class AbstractComponent
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
     * Checks if the components needs a container.
     * 
     * @return boolean
     */
    public function needsContainer()
    {
        return $this->container;
    }

    /**
     * Returns the class name;
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->className;
    }
}

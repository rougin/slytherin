<?php

namespace Rougin\Slytherin\Component;

/**
 * Methods used for integrating a component to Slytherin.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
abstract class AbstractComponent implements ComponentInterface
{
    /**
     * Type of the component:
     * "container", "dispatcher", "debugger",
     * "http", "middleware", and "template"
     *
     * @var string
     */
    protected $type = '';

    /**
     * Registers the component to the specified collection.
     *
     * @param \Rougin\Slytherin\Component\Collection $collection
     *
     * @return void
     */
    abstract public function register(Collection $collection);

    /**
     * Returns the type of the component.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

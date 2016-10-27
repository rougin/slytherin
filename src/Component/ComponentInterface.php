<?php

namespace Rougin\Slytherin\Component;

/**
 * Component Interface
 *
 * An interface for handling components.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ComponentInterface
{
    /**
     * Sets the component. Can also add it to the container.
     *
     * @param  \Interop\Container\ContainerInterface &$container
     * @return void
     */
    public function set(\Interop\Container\ContainerInterface &$container);
}

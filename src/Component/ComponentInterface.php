<?php

namespace Rougin\Slytherin\Component;

use Interop\Container\ContainerInterface;

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
     * Sets the component and add it to the container of your choice.
     *
     * @param  \Interop\Container\ContainerInterface &$container
     * @return void
     */
    public function set(ContainerInterface &$container);
}

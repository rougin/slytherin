<?php

namespace Rougin\Slytherin\Component;

/**
 * An interface for handling components.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface ComponentInterface
{
    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    public function get();

    /**
     * Returns the type of the component.
     *
     * The following types of component are:
     * "container", "dispatcher", "debugger",
     * "http", "middleware", or "template"
     *
     * @return string
     */
    public function getType();
}

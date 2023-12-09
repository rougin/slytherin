<?php

namespace Rougin\Slytherin\Component;

/**
 * Component Interface
 *
 * An interface for handling components.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
     * @return string
     */
    public function getType();
}

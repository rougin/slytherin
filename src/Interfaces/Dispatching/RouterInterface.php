<?php

namespace Rougin\Slytherin\Interfaces\Dispatching;

/**
 * Router Interface
 *
 * An interface for handling third party routers
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface RouterInterface
{
    /**
     * Dispatches against the provided HTTP method verb and URI
     * 
     * @return array
     */
    public function dispatch();
}

<?php

namespace Rougin\Slytherin\Debug;

/**
 * Error Handler Interface
 *
 * An interface for handling third party error handlers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface ErrorHandlerInterface
{
    /**
     * Registers the instance as an error handler.
     *
     * @return object
     */
    public function display();
}

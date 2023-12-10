<?php

namespace Rougin\Slytherin\Debug;

/**
 * Error Handler Interface
 *
 * An interface for handling third-party error handlers.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface ErrorHandlerInterface
{
    /**
     * Registers the instance as an error handler.
     *
     * @return void
     */
    public function display();
}

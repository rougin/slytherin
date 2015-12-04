<?php

namespace Rougin\Slytherin\ErrorHandler;

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
     * Sets up the environment to be used.
     * 
     * @param  string $environment
     * @return void
     */
    public function setEnvironment($environment);

    /**
     * Registers the instance as an error handler.
     * 
     * @return object
     */
    public function display();
}

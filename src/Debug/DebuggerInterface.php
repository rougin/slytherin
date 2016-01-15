<?php

namespace Rougin\Slytherin\Debug;

/**
 * Debugger Interface
 *
 * An interface for handling third party debuggers.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface DebuggerInterface
{
    /**
     * Sets up the environment to be used.
     * 
     * @param  string $environment
     * @return void
     */
    public function setEnvironment($environment);

    /**
     * Gets the specified environment.
     *
     * @return string
     */
    public function getEnvironment();

    /**
     * Registers the instance as a debugger.
     * 
     * @return object
     */
    public function display();
}

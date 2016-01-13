<?php

namespace Rougin\Slytherin\Debug;

/**
 * Debugger
 *
 * A simple implementation of a debugger.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Debugger implements DebuggerInterface
{
    /**
     * @var string
     */
    protected $environment = '';

    /**
     * @param string $environment
     */
    public function __construct($environment = 'development')
    {
        $this->environment = $environment;
    }

    /**
     * Sets up the environment to be used.
     * 
     * @param  string $environment
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Registers the instance as a debugger.
     * 
     * @return string
     */
    public function display()
    {
        error_reporting(E_ALL);

        return '';
    }
}

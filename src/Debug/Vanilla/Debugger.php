<?php

namespace Rougin\Slytherin\Debug\Vanilla;

use Rougin\Slytherin\Debug\DebuggerInterface;

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
     * Gets the specified environment.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
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

<?php

namespace Rougin\Slytherin\Debug;

/**
 * Error Handler
 *
 * A simple implementation of a debugger.
 * NOTE: To be removed in v1.0.0. Use "ErrorHandlerIntegration" instead.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ErrorHandler implements ErrorHandlerInterface
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
     * NOTE: To be removed in v1.0.0.
     *
     * @param  string $environment
     * @return self
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Gets the specified environment.
     * NOTE: To be removed in v1.0.0.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Registers the instance as an error handler.
     *
     * @return mixed
     */
    public function display()
    {
        error_reporting(E_ALL);

        ini_set('display_errors', '1');
    }
}
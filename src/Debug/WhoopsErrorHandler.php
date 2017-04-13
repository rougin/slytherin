<?php

namespace Rougin\Slytherin\Debug;

/**
 * Whoops Error Handler
 *
 * A simple implementation of an error handler built on top of Filipe Dobreira's
 * Whoops. NOTE: To be removed in v1.0.0. Use "ErrorHandlerIntegration" instead.
 *
 * http://filp.github.io/whoops
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WhoopsErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var string
     */
    protected $environment = '';

    /**
     * @var \Whoops\Run
     */
    protected $whoops;

    /**
     * @param \Whoops\Run $whoops
     * @param string      $environment
     */
    public function __construct(\Whoops\Run $whoops, $environment = 'development')
    {
        $this->environment = $environment;

        $this->whoops = $whoops;
    }

    /**
     * Sets up the environment to be used.
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
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Returns a listing of handlers.
     *
     * @return \Whoops\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->whoops->getHandlers();
    }

    /**
     * Registers the instance as a debugger.
     *
     * @return \Whoops\Run
     */
    public function display()
    {
        error_reporting(E_ALL);

        if ($this->environment === 'development') {
            $this->setHandler(new \Whoops\Handler\PrettyPageHandler);
        }

        return $this->whoops->register();
    }

    /**
     * Sets a handler.
     *
     * @param \Whoops\Handler\HandlerInterface|callable $handler
     */
    public function setHandler($handler)
    {
        $this->whoops->pushHandler($handler);
    }
}

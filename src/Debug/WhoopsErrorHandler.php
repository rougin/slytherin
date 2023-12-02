<?php

namespace Rougin\Slytherin\Debug;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Whoops Error Handler
 *
 * A simple implementation of an error handler built on top of Filipe Dobreira's
 * Whoops. NOTE: To be removed in v1.0.0. Use "ErrorHandlerIntegration" instead.
 *
 * http://filp.github.io/whoops
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
    public function __construct(Run $whoops, $environment = 'development')
    {
        $this->environment = $environment;

        $this->whoops = $whoops;
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
     * Returns a listing of handlers.
     * NOTE: To be removed in v1.0.0. Use __call() instead.
     *
     * @return \Whoops\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->whoops->getHandlers();
    }

    /**
     * Registers the instance as an error handler.
     *
     * @return mixed
     */
    public function display()
    {
        $handler = new PrettyPageHandler;

        error_reporting(E_ALL);

        $this->__call('pushHandler', array($handler));

        return $this->whoops->register();
    }

    /**
     * Sets a handler.
     * NOTE: To be removed in v1.0.0. Use __call() instead.
     *
     * @param  \Whoops\Handler\HandlerInterface|callable $handler
     * @return void
     */
    public function setHandler($handler)
    {
        $this->whoops->pushHandler($handler);
    }

    /**
     * Calls methods from the \Whoops\Run instance.
     *
     * @param  string  $method
     * @param  mixed[] $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        /** @var callable */
        $class = array($this->whoops, $method);

        return call_user_func_array($class, $params);
    }
}

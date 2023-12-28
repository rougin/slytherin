<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Debug;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Whoops Error Handler
 *
 * A simple implementation of an error handler built on top of Whoops.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 *
 * @link https://filp.github.io/whoops
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
     * NOTE: To be removed in v1.0.0. It is not part of the "ErrorHandlerInterface".
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
     * Returns the specified environment.
     * NOTE: To be removed in v1.0.0. It is not part of the "ErrorHandlerInterface".
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Returns a listing of handlers.
     * NOTE: To be removed in v1.0.0. Use the magic method "__call" instead.
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
     * @return void
     */
    public function display()
    {
        $handler = new PrettyPageHandler;

        error_reporting(E_ALL);

        $this->__call('pushHandler', array($handler));

        $this->whoops->register();
    }

    /**
     * Sets a handler.
     * NOTE: To be removed in v1.0.0. Use __call" instead.
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

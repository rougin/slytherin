<?php

namespace Rougin\Slytherin\Routing;

/**
 * Abstract Dispatcher
 *
 * An abstract class for common methods in route dispatchers.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class AbstractDispatcher
{
    /**
     * @var array
     */
    protected $allowed = array('DELETE', 'GET', 'OPTIONS', 'PATCH', 'POST', 'PUT');

    /**
     * Initializes the route dispatcher instance.
     *
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     */
    public function __construct(RouterInterface $router = null)
    {
        if ($router instanceof RouterInterface)
        {
            $this->router($router);
        }
    }

    /**
     * Checks if the specified method is a valid HTTP method.
     *
     * @param  string $method
     * @return boolean
     *
     * @throws UnexpectedValueException
     */
    protected function allowed($method)
    {
        if (in_array($method, $this->allowed) === false) {
            $message = 'Used method is not allowed';

            throw new \UnexpectedValueException($message);
        }

        return true;
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    abstract public function router(RouterInterface $router);
}

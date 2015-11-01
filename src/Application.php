<?php

namespace Rougin\Slytherin;

use Rougin\Slytherin\ComponentCollection;

/**
 * Application
 *
 * Integrates all specified components into the application.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application
{
    private $components;

    /**
     * @param ComponentCollection $components
     */
    public function __construct(ComponentCollection $components)
    {
        $this->components = $components;
    }

    /**
     * Runs the application
     * 
     * @return void
     */
    public function run()
    {
        if ($this->components->getErrorHandler()) {
            $errorHandler = $this->components->getErrorHandler();
            $errorHandler->display();
        }

        $this->components->getHttp('response')->setContent(
            $this->getRoute()
        );

        echo $this->components->getHttp('response')->getContent();
    }

    /**
     * Gets the route result from the dispatcher.
     *
     * @return array|string
     */
    protected function getRoute()
    {
        // Gets the requested route
        $route = $this->components->getDispatcher()->dispatch(
            $this->components->getHttp('request')->getMethod(),
            $this->components->getHttp('request')->getPath()
        );

        // Returns the received route if it is a string
        if (is_string($route)) {
            return $route;
        }

        // Extract the result into variables
        list($class, $method, $parameters) = $route;
        $class = $this->components->getDependencyInjector()->make($class);

        return call_user_func_array([$class, $method], $parameters);
    }
}

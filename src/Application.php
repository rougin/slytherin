<?php

namespace Rougin\Slytherin;

use Rougin\Slytherin\Interfaces\Dispatching\RouterInterface;
use Rougin\Slytherin\Interfaces\ErrorHandling\ErrorHandlerInterface;
use Rougin\Slytherin\Interfaces\Http\RequestInterface;
use Rougin\Slytherin\Interfaces\Http\ResponseInterface;
use Rougin\Slytherin\Interfaces\IoC\DependencyInjectorInterface;

/**
 * Application Class
 *
 * Integrates all specified components into the application
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application
{
    private $components;

    /**
     * @param array  $components
     * @param object $injector
     */
    public function __construct($components)
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
        $this->setErrorHandler($this->components['error_handler']);

        list($request, $response) = $this->setHttp(
            $this->components['request'],
            $this->components['response']
        );

        $injector = $this->components['dependency_injector'];
        $result = $this->setRouter($this->components['router'], $injector);

        /**
         * Return the content
         */

        $response->setContent($result);
        echo $response->getContent();
    }

    /**
     * Sets up the error handler if included
     * 
     * @param  ErrorHandlerInterface $errorHandler
     * @return object|void
     */
    protected function setErrorHandler(
        ErrorHandlerInterface $errorHandler = null
    ) {
        if ( ! $errorHandler) {
            return;
        }

        return $errorHandler->display();
    }

    /**
     * Sets up the HTTP components
     * 
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @return array
     */
    protected function setHttp(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        return [$request, $response];
    }

    /**
     * Sets up the router to handle route requests
     * 
     * @param  RouterInterface             $router
     * @param  DependencyInjectorInterface $injector
     * @return void
     */
    protected function setRouter(
        RouterInterface $router,
        DependencyInjectorInterface $injector
    ) {
        /**
         * Get the request route
         */

        $route = $router->dispatch();

        /**
         * Return the received route if it is a string
         */

        if (is_string($route)) {
            return $route;
        }

        /**
         * Return the received route to a dependency injector if the result
         * contains a class name, method and its parameters
         */

        list($class, $method, $parameters) = $route;
        $class = $injector->make($class);

        return call_user_func_array([$class, $method], $parameters);
    }
}

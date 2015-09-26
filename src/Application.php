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
     * @param array $components
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

        $http = $this->setHttp(
            $this->components['request'],
            $this->components['response']
        );

        $injector = $this->components['dependency_injector'];
        $result = $this->setRouter($this->components['router'], $injector);

        // Sets and returns the content
        $http->response->setContent($result);
        echo $http->response->getContent();
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
        return (object) ['request' => $request, 'response' => $response];
    }

    /**
     * Sets up the router to handle route requests
     * 
     * @param  RouterInterface             $router
     * @param  DependencyInjectorInterface $injector
     * @return array|string
     */
    protected function setRouter(
        RouterInterface $router,
        DependencyInjectorInterface $injector
    ) {
        // Gets the request route
        $route = $router->dispatch();

        // Returns the received route if it is a string
        if (is_string($route)) {
            return $route;
        }

        // Extract the result into variables
        list($class, $method, $parameters) = $route;
        $class = $injector->make($class);

        return call_user_func_array([$class, $method], $parameters);
    }
}

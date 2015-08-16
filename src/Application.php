<?php

namespace Rougin\Slytherin;

use ReflectionClass;

/**
 * Application Class
 *
 * The place where we integrate the components and dispatch the specified
 * route to the application
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application
{
    private $components;
    private $config;
    private $content;
    private $injector;
    private $request;
    private $response;

    /**
     * @param array  $config
     * @param array  $components
     * @param object $injector
     */
    public function __construct($config, $components, $injector)
    {
        $this->config = $config;
        $this->components = $components;
        $this->injector = $injector;
    }

    /**
     * Run the application
     * 
     * @return void
     */
    public function run()
    {
        $this->setErrorHandler(
            $this->components['error_handler'],
            $this->config['environment']
        );

        $this->setHttp(
            $this->components['request'],
            $this->components['response']
        );

        $this->setRouter($this->components['router']);

        /**
         * Return the list of headers if any
         */

        if (is_array($this->response->getHeaders())) {
            foreach ($this->response->getHeaders() as $header) {
                header($header);
            }
        }

        /**
         * Return the content
         */

        echo $this->response->getContent();
    }

    /**
     * Check if there is a dependency injector include from the
     * list of components
     * 
     * @return boolean
     */
    protected function hasDependencyInjector()
    {
        return is_object($this->injector) ? TRUE : FALSE;
    }

    /**
     * Sets up the error handler if included
     *
     * @return object|integer
     */
    protected function setErrorHandler($errroHandler, $environment)
    {
        error_reporting(E_ALL);

        /**
         * Check if an error handler is included in the list of components
         */

        if (!$errroHandler) {
            return 0;
        }

        /**
         * If the included error handler is a string, then use a dependency
         * injector to instantiate the said error handler
         */

        if (is_string($errroHandler) && $this->hasDependencyInjector()) {
            $errroHandler = $this->injector->make($errroHandler);
        }

        /**
         * Set up the environment to be used in the application
         */

        $errroHandler->setEnvironment($environment);

        /**
         * Run the specified error handler
         */

        return $errroHandler->display();
    }

    /**
     * Sets up the HTTP components
     *
     * @return void
     */
    protected function setHttp($request, $response)
    {
        /**
         * If the included request object is a string, then use a dependency
         * injector to instantiate the said request object
         */

        if (is_string($request) && $this->hasDependencyInjector()) {
            $this->request = $this->injector->make($request);
        } else {
            $this->request = $request;
        }

        /**
         * If the included response object is a string, then use a dependency
         * injector to instantiate the said response object
         */

        if (is_string($response) && $this->hasDependencyInjector()) {
            $this->response = $this->injector->make($response);
        } else {
            $this->response = $response;
        }
    }

    /**
     * Sets up the router to handle route requests
     *
     * @return void
     */
    protected function setRouter($router)
    {
        /**
         * If the included router is a string, then use a dependency injector
         * to instantiate the said router
         */

        if (is_string($router) && $this->hasDependencyInjector()) {
            $router = $this->injector->make($router);
        }

        /**
         * Get the request route
         */

        $route = $router->dispatch();

        /**
         * Return the received route if it is a string
         */

        if (is_string($route)) {
            $this->response->setContent($route);

            return;
        }

        /**
         * Return the received route to a dependency injector if the result
         * contains a class name, method and its parameters
         */

        list($className, $method, $parameters) = $route;

        if ($this->hasDependencyInjector()) {
            $class = $this->injector->make($className);
        } else {
            $reflect = new ReflectionClass($className);
            $class = $reflect->newInstanceArgs($parameters);
        }

        $this->response->setContent(call_user_func_array([$class, $method], $parameters));
    }
}

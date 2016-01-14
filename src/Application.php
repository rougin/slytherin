<?php

namespace Rougin\Slytherin;

use Rougin\Slytherin\Components;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
    /**
     * @var \Rougin\Slytherin\Components
     */
    private $components;

    /**
     * @param \Rougin\Slytherin\Components $components
     */
    public function __construct(Components $components)
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
        if ($debugger = $this->components->getDebugger()) {
            $debugger->display();
        }

        list($request, $response) = $this->components->getHttp();

        $route = $this->getRoute($request);

        if ($route instanceof ResponseInterface) {
            $response = $route;
        }

        // Sets the HTTP response code.
        http_response_code($response->getStatusCode());

        // Gets the selected route and sets it as the content.
        if ( ! $response->getBody() || $response->getBody() == '') {
            $response->getBody()->write($route);
        }

        echo $response->getBody();
    }

    /**
     * Gets the route result from the dispatcher.
     * 
     * @param  \Psr\Http\Message\RequestInterface $request
     * @return string
     */
    private function getRoute(RequestInterface $request)
    {
        // Gets the requested route
        $route = $this->components->getDispatcher()->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        // Extract the result into variables
        list($function, $parameters) = $route;

        if (is_object($function)) {
            return call_user_func($function, $parameters);
        }

        list($class, $method) = $function;

        if ( ! $this->components->getContainer()->has($class)) {
            $this->components->getContainer()->add($class);
        }

        $class = $this->components->getContainer()->get($class);

        return call_user_func_array([$class, $method], $parameters);
    }
}

<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Rougin\Slytherin\IoC\ContainerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

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

        $container = $this->components->getContainer();
        $middleware = $this->components->getMiddleware();

        list($request, $response) = $this->components->getHttp();

        $result = $this->dispatch($container, $request, $response, $middleware);

        if ($result instanceof ResponseInterface) {
            $response = $result;
        } else {
            $response->getBody()->write($result);
        }

        // Sets the specified headers, if any.
        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        // Sets the HTTP response code.
        http_response_code($response->getStatusCode());

        echo $response->getBody();
    }

    /**
     * Gets the result from the dispatcher.
     *
     * @param  \Rougin\Slytherin\IoC\ContainerInterface $container
     * @param  \Psr\Http\Message\RequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @return \Psr\Http\Message\ResponseInterface|mixed
     */
    private function dispatch(
        ContainerInterface $container,
        RequestInterface $request,
        ResponseInterface $response,
        MiddlewareInterface $middleware = null
    ) {
        // Gets the requested route from the dispatcher
        $route = $this->components->getDispatcher()->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        // Extract the result into variables
        list($function, $parameters, $middlewares) = $route;

        $result = null;

        if ($middleware && ! empty($middlewares)) {
            $result = $middleware($request, $response, $middlewares);
        }

        if ($result && $result->getBody(true) != '') {
            return $result;
        }

        // If the function is a callback, return immediately.
        if (is_callable($function) && is_object($function)) {
            return call_user_func($function, $parameters);
        }

        list($class, $method) = $function;

        if ( ! $container->has($class)) {
            $container->add($class);
        }

        $class = $container->get($class);

        return call_user_func_array([$class, $method], $parameters);
    }
}

<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @var \Rougin\Slytherin\ComponentCollection
     */
    private $components;

    /**
     * @param \Rougin\Slytherin\ComponentCollection $components
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
        $request = $this->components->getHttpRequest();

        if ($debugger = $this->components->getDebugger()) {
            $debugger->display();
        }

        list($function, $middlewares) = $this->dispatchRoute($request);

        if ( ! $response = $this->prepareMiddleware($middlewares)) {
            $response = $this->setResponse($this->resolveClass($function));
        }

        echo $response->getBody();
    }

    /**
     * Gets the result from the dispatcher.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     */
    private function dispatchRoute(ServerRequestInterface $request)
    {
        $dispatcher = $this->components->getDispatcher();
        $path = $request->getUri()->getPath();

        // Gets the requested route from the dispatcher
        $route = $dispatcher->dispatch($request->getMethod(), $path);

        // Extract the result into variables
        list($function, $parameters, $middlewares) = $route;

        return [ [ $function, $parameters ], $middlewares ];
    }

    /**
     * Prepares the defined middlewares.
     * 
     * @param  array $middlewares
     * @return mixed
     */
    private function prepareMiddleware($middlewares = [])
    {
        $middleware = $this->components->getMiddleware();
        $result = null;

        list($request, $response) = $this->components->getHttp();

        if ($middleware && ! empty($middlewares)) {
            $result = $middleware($request, $response, $middlewares);
        }

        if ($result instanceof ResponseInterface) {
            return $this->setResponse($result);
        }

        return $result;
    }

    /**
     * Resolves the result based from the dispatched route.
     * 
     * @param  array $function
     * @return mixed
     */
    private function resolveClass($function)
    {
        list($class, $parameters) = $function;

        if (is_callable($class) && is_object($class)) {
            return call_user_func($class, $parameters);
        }

        $container = $this->components->getContainer();

        list($className, $method) = $class;

        if ( ! $container->has($className)) {
            $container->add($className);
        }

        $result = $container->get($className);

        return call_user_func_array([ $result, $method ], $parameters);
    }

    /**
     * Sets the response to the user.
     * 
     * @param  mixed $result
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function setResponse($result)
    {
        $response = $this->components->getHttpResponse();

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

        return $response;
    }
}

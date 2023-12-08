<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Component\Collection;

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
     * @var \Rougin\Slytherin\Component\Collection
     */
    private $components;

    /**
     * @param \Rougin\Slytherin\Component\Collection $components
     */
    public function __construct(Collection $components)
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

        $response = $this->prepareMiddleware($middlewares);

        if ( ! $response || $response->getBody() == '') {
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
        $method = $request->getMethod();
        $post = $request->getParsedBody();
        $path = $request->getUri()->getPath();

        if (isset($post['_method'])) {
            $method = strtoupper($post['_method']);
        }

        // Gets the requested route from the dispatcher
        $route = $dispatcher->dispatch($method, $path);

        // Extracts the result into variables
        list($function, $parameters) = $route;

        // If not set, set as empty by default --------
        $middlewares = array();

        if (isset($route[2])) $middlewares = $route[2];
        // --------------------------------------------

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

        return ($result) ? $this->setResponse($result) : null;
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

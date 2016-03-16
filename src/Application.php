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
     * Handles a request to convert it to a response.
     *
     * @param  \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(RequestInterface $request)
    {
        // Gets the specified components
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();
        $response = $this->components->getResponse();

        // Gets the requested route from the dispatcher.
        $httpMethod = $request->getMethod();
        $uri = $request->getUri()->getPath();
        $result = $dispatcher->dispatch($httpMethod, $uri);

        // Extracts the result into variables.
        list($function, $parameters, $middlewares) = $result;

        // Calls the specified middlewares.
        if ($middleware && ! empty($middlewares)) {
            $response = $middleware($request, $response, $middlewares);
        }

        if ($response->getBody(true) != '') {
            return $response;
        }

        return $this->toResponse($function, $parameters);
    }

    /**
     * Runs the application.
     * 
     * @return void
     */
    public function run()
    {
        if ($debugger = $this->components->getDebugger()) {
            $debugger->display();
        }

        $response = $this->handle($this->components->getRequest());

        // Sets the specified headers, if any.
        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        // Sets the HTTP response code.
        http_response_code($response->getStatusCode());

        echo $response->getBody();
    }

    /**
     * Converts the returned data into a response.
     * 
     * @param  array|callable $function
     * @param  array          $parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function toResponse($function, array $parameters = [])
    {
        $container = $this->components->getContainer();
        $response = null;

        // If the function is a callback.
        if (is_callable($function) && is_object($function)) {
            $response = call_user_func($function, $parameters);
        }

        // If the function returns an array.
        if (is_array($function)) {
            list($class, $method) = $function;

            if ( ! $container->has($class)) {
                $container->add($class);
            }

            $class = $container->get($class);

            $response = call_user_func_array([$class, $method], $parameters);
        }

        // Checks if the result does not have instance of ResponseInterface.
        if ( ! $response instanceof ResponseInterface) {
            $result = $response;
            $response = $this->components->getResponse();

            $response->getBody()->write($result);
        }

        return $response;
    }
}

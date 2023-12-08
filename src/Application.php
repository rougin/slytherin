<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\ResponseInterface;
use Rougin\Slytherin\Http\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Application
 *
 * Integrates all specified components into the application.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Application extends HttpFoundationFactory implements HttpKernelInterface
{
    /**
     * @var \Rougin\Slytherin\Components
     */
    protected $components;

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
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @param  integer $type
     * @param  boolean $catch
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        // Gets the specified components
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();
        $response = $this->components->getHttpResponse();

        // Gets the requested route from the dispatcher.
        $httpMethod = $request->getMethod();
        $uri = $request->getPathInfo();
        $result = $dispatcher->dispatch($httpMethod, $uri);

        // Extracts the result into variables.
        list($function, $parameters) = $result;

        // If not set, set as empty by default ----------
        $middlewares = array();

        if (isset($result[2])) $middlewares = $result[2];
        // ----------------------------------------------

        // Calls the specified middlewares.
        if ($middleware && ! empty($middlewares)) {
            $psrRequest = $this->components->getHttpRequest();
            $response = $middleware($psrRequest, $response, $middlewares);
        }

        // Converts the result into a Symfony response.
        if ($response && $response->getBody(true) != '') {
            return $this->createResponse($response);
        }

        $response = $this->createPsrResponse($function, $parameters);

        return $this->createResponse($response);
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

        $psrRequest = $this->components->getHttpRequest();
        $symfonyRequest = $this->createRequest($psrRequest);
        $symfonyResponse = $this->handle($symfonyRequest);

        $symfonyResponse->send();
    }

    /**
     * Converts the returned data into a PSR-compliant response.
     * 
     * @param  array|callable $function
     * @param  array $parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function createPsrResponse($function, array $parameters = [])
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
            $response = $this->components->getHttpResponse();

            $response->getBody()->write($result);
        }

        return $response;
    }
}

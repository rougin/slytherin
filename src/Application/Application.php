<?php

namespace Rougin\Slytherin\Application;

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
     * @var \Interop\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Rougin\Slytherin\Debug\ErrorHandlerInterface
     */
    protected $debugger = null;

    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var \Rougin\Slytherin\Middleware\MiddlewareInterface
     */
    protected $middleware = null;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(\Interop\Container\ContainerInterface $container)
    {
        $this->container = $container;

        $this->dispatcher = $container->get('Rougin\Slytherin\Routing\DispatcherInterface');
        $this->request    = $container->get('Psr\Http\Message\ServerRequestInterface');
        $this->response   = $container->get('Psr\Http\Message\ResponseInterface');

        if ($container->has('Rougin\Slytherin\Middleware\MiddlewareInterface')) {
            $this->middleware = $container->get('Rougin\Slytherin\Middleware\MiddlewareInterface');
        }

        // NOTE: To be removed in v1.0.0
        if ($container->has('Rougin\Slytherin\Debug\ErrorHandlerInterface')) {
            $this->debugger = $container->get('Rougin\Slytherin\Debug\ErrorHandlerInterface');
        }
    }

    /**
     * Handles a ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $classResolver   = new ClassResolver($this->container);
        $httpModifier    = new HttpModifier($this->response);
        $routeDispatcher = new RouteDispatcher($this->dispatcher);

        $contents = $routeDispatcher->dispatch($request);

        list($function, $middlewares) = $contents;

        $httpModifier->setMiddlewares($middlewares, $this->middleware);

        $first = $httpModifier->invokeMiddleware($request, $this->middleware);
        $final = $classResolver->resolveClass($function);

        return $httpModifier->setHttpResponse($final, $first);
    }

    /**
     * Runs the application
     *
     * @return void
     */
    public function run()
    {
        if ($this->debugger && $this->debugger->getEnvironment() == 'development') {
            $this->debugger->display();
        }

        echo (string) $this->handle($this->request)->getBody();
    }
}

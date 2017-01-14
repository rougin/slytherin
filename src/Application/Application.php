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
     * @var \Rougin\Slytherin\Component\Collection
     */
    private $components;

    /**
     * @param \Rougin\Slytherin\Component\Collection $components
     */
    public function __construct(\Rougin\Slytherin\Component\Collection $components)
    {
        $this->components = $components;
    }

    /**
     * Handles a ServerRequestInterface to convert it to a ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $classResolver   = new ClassResolver;
        $httpModifier    = new HttpModifier($this->components->getHttpResponse());
        $routeDispatcher = new RouteDispatcher;

        $container  = $this->components->getContainer();
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();

        $contents = $routeDispatcher->dispatch($dispatcher, $request);

        list($function, $middlewares) = $contents;

        $httpModifier->setMiddlewares($middlewares);

        $result = $httpModifier->invokeMiddleware($request, $middleware);

        if (! $result || $result->getBody() == '') {
            $result = $classResolver->resolveClass($container, $function);
        }

        return $httpModifier->setHttpResponse($result);
    }

    /**
     * Runs the application
     *
     * @return void
     */
    public function run()
    {
        $debugger = $this->components->getDebugger();
        $request  = $this->components->getHttpRequest();

        if ($debugger && $debugger->getEnvironment() == 'development') {
            $debugger->display();
        }

        echo (string) $this->handle($request)->getBody();
    }
}

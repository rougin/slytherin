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
        $classResolver   = new ClassResolver($this->components->getContainer());
        $httpModifier    = new HttpModifier($this->components->getHttpResponse());
        $routeDispatcher = new RouteDispatcher($this->components->getDispatcher());

        $contents = $routeDispatcher->dispatch($request);

        list($function, $middlewares) = $contents;

        $middleware = $this->components->getMiddleware();

        $httpModifier->setMiddlewares($middlewares, $middleware);

        $first = $httpModifier->invokeMiddleware($request, $middleware);
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
        $handler = $this->components->getDebugger();
        $request = $this->components->getHttpRequest();

        if ($handler && $handler->getEnvironment() == 'development') {
            $handler->display();
        }

        echo (string) $this->handle($request)->getBody();
    }
}

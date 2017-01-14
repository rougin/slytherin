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
        $helpers = array(new ClassResolver, new RouteDispatcher, new HttpModifier($this->components->getHttpResponse()));

        $container  = $this->components->getContainer();
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();

        $contents = $helpers[1]->dispatch($dispatcher, $request);

        list($function, $middlewares) = $contents;

        $helpers[2]->setMiddlewares($middlewares);

        $result = $helpers[2]->invokeMiddleware($request, $middleware);

        if (! $result || $result->getBody() == '') {
            $result = $helpers[0]->resolveClass($container, $function);
        }

        return $helpers[2]->setHttpResponse($result);
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

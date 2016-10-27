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
    use Traits\DispatchRouteTrait,
        Traits\PrepareHttpResponseTrait,
        Traits\PrepareMiddlewaresTrait,
        Traits\ResolveClassTrait;

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
        $container  = $this->components->getContainer();
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();
        $response   = $this->components->getHttpResponse();

        list($function, $middlewares) = $this->dispatchRoute($dispatcher, $request);

        $result = $this->prepareMiddlewares($request, $response, $middleware, $middlewares);

        if (! $result || $result->getBody() == '') {
            $result = $this->resolveClass($container, $function);
        }

        return $this->prepareHttpResponse($result, $response);
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

        echo $this->handle($request)->getBody();
    }
}

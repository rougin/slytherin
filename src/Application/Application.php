<?php

namespace Rougin\Slytherin\Application;

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
        $container  = $this->components->getContainer();
        $debugger   = $this->components->getDebugger();
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();
        $request    = $this->components->getHttpRequest();
        $response   = $this->components->getHttpResponse();

        if ($debugger && $debugger->getEnvironment() == 'development') {
            $debugger->display();
        }

        list($function, $middlewares) = $this->dispatchRoute($dispatcher, $request);

        $result = $this->prepareMiddlewares($middleware, $request, $response, $middlewares);

        if (! $result || $result->getBody() == '') {
            $classObject = $this->resolveClass($container, $function);
            $result      = $this->prepareHttpResponse($classObject, $response);
        }

        echo $result->getBody();
    }
}

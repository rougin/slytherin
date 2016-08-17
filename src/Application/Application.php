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
        // Traits\PrepareMiddlewaresTrait,
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

        if ($debugger) {
            $debugger->display();
        }

        list($function, $middlewares) = $this->dispatchRoute($dispatcher, $request);

        if ($middleware && ! empty($middlewares)) {
            $response = $middleware($request, $response, $middlewares);
        }

        if ( ! $response || $response->getBody() == '') {
            $class    = $this->resolveClass($container, $function);
            $response = $this->prepareHttpResponse($class);
        }

        echo $response->getBody();
    }
}

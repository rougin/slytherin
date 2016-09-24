<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ResponseInterface;

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
        list($request, $response) = $this->components->getHttp();

        $container  = $this->components->getContainer();
        $debugger   = $this->components->getDebugger();
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();

        if ($debugger && $debugger->getEnvironment() == 'development') {
            $debugger->display();
        }

        list($function, $middlewares) = $this->dispatchRoute($dispatcher, $request);

        $result = $this->prepareMiddlewares($middleware, $middlewares);

        if (! $result || $result->getBody() == '') {
            $result = $this->resolveClass($container, $function);
        }

        $result = $this->prepareHttpResponse($result, $response);

        echo $result->getBody();
    }
}

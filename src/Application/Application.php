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
        $container  = $this->components->getContainer();
        $debugger   = $this->components->getDebugger();
        $dispatcher = $this->components->getDispatcher();
        $middleware = $this->components->getMiddleware();
        $request    = $this->components->getHttpRequest();
        $response   = $this->components->getHttpResponse();

        if ($debugger && $debugger->getEnvironment() == 'development') {
            $debugger->display();
        }

        $result = $this->dispatchRoute($dispatcher, $request);

        // NOTE: To be removed in v1.0.0
        if (is_array($result)) {
            list($function, $middlewares) = $result;

            $result = $this->prepareMiddlewares($middleware, $middlewares);

            if (! $result || $result->getBody() == '') {
                $result = $this->resolveClass($container, $function);
            }
        }

        $result = $this->prepareHttpResponse($result, $response);

        echo $result->getBody();
    }
}

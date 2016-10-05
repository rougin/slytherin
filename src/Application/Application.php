<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ServerRequestInterface;
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

    const MASTER_REQUEST = 1;
    const SUB_REQUEST    = 2;

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
     * Handles a ServerRequestInterface to convert it to a ResponseInterface.
     * 
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  integer                                  $type
     * @param  boolean                                  $catch
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception When an Exception occurs during processing
     */
    public function handle(ServerRequestInterface $request, $type = self::MASTER_REQUEST, $catch = true)
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

        if ($debugger && $debugger->getEnvironment() == 'development') {
            $debugger->display();
        }

        $request  = $this->components->getHttpRequest();
        $response = $this->handle($request, self::MASTER_REQUEST, true);

        echo $response->getBody();
    }
}

<?php

namespace Rougin\Slytherin\Application;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Middleware\Delegate;
use Rougin\Slytherin\Middleware\Dispatcher;

/**
 * Callback Handler
 *
 * Handles the final callback to be used in the application.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class CallbackHandler
{
    const DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

    const ROUTER = 'Rougin\Slytherin\Routing\RouterInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

    /**
     * @var \Rougin\Slytherin\Application\FinalCallback
     */
    protected $callback;

    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @var array<int, \Closure|\Interop\Http\ServerMiddleware\MiddlewareInterface|string>
     */
    protected $middlewares = array();

    /**
     * Initializes the handler instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = new Container(array(), $container);
    }

    /**
     * Returns a response instance.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        /** @var \Rougin\Slytherin\Routing\DispatcherInterface */
        $dispatcher = $this->container->get(self::DISPATCHER);

        if ($this->container->has(self::ROUTER))
        {
            /** @var \Rougin\Slytherin\Routing\RouterInterface */
            $router = $this->container->get(self::ROUTER);

            $dispatcher = $dispatcher->setRouter($router);
        }

        $path = $request->getUri()->getPath();

        $method = $request->getMethod();

        $route = $dispatcher->dispatch($method, $path);

        $this->middlewares = $route->getMiddlewares();

        $callback = new FinalCallback($route, $this->container);

        return $this->middleware($callback, $request);
    }

    /**
     * Dispatches the middlewares of the specified request, if there are any.
     *
     * @param  \Rougin\Slytherin\Application\FinalCallback $callback
     * @param  \Psr\Http\Message\ServerRequestInterface    $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function middleware(FinalCallback $callback, ServerRequestInterface $request)
    {
        $exists = interface_exists(Application::MIDDLEWARE);

        if (! $exists) return $callback($request);

        /** @var \Psr\Http\Message\ResponseInterface */
        $response = $this->container->get(self::RESPONSE);

        $middleware = new Dispatcher($this->middlewares, $response);

        $delegate = new Delegate($callback);

        return $middleware->process($request, $delegate);
    }
}

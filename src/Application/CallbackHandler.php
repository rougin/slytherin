<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Callback Handler
 *
 * Handles the final callback to be used in the application.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CallbackHandler
{
    const ROUTE_DISPATCHER = 'Rougin\Slytherin\Routing\DispatcherInterface';

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
     * Sets up the container.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(\Psr\Container\ContainerInterface $container)
    {
        $this->container = new \Rougin\Slytherin\Container\Container(array(), $container);
    }

    /**
     * Returns a \Psr\Http\Message\ResponseInterface.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return callback
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $dispatcher = $this->container->get(self::ROUTE_DISPATCHER);

        if ($this->container->has(self::ROUTER)) {
            $router = $this->container->get(self::ROUTER);

            $dispatcher = $dispatcher->router($router);
        }

        list($method, $path) = array($request->getMethod(), $request->getUri()->getPath());

        list($function, $middlewares) = $dispatcher->dispatch($method, $path);

        $callback = new FinalCallback($this->container, $function);

        return $this->middleware($callback, $request, $middlewares);
    }

    /**
     * Dispatches the middlewares of the specified request, if there are any.
     *
     * @param  \Rougin\Slytherin\Application\FinalCallback $callback
     * @param  \Psr\Http\Message\ServerRequestInterface    $request
     * @param  array                                       $middlewares
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    protected function middleware(FinalCallback $callback, ServerRequestInterface $request, array $middlewares = array())
    {
        list($result, $response) = array(null, $this->container->get(self::RESPONSE));

        if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $middleware = new \Rougin\Slytherin\Middleware\Dispatcher($middlewares, $response);

            $delegate = new \Rougin\Slytherin\Middleware\Delegate($callback);

            $result = $middleware->process($request, $delegate);
        }

        return is_null($result) ? $callback($request) : $result;
    }
}

<?php

namespace Rougin\Slytherin\Routing;

use Phroute\Phroute\Dispatcher as Phroute;
use Phroute\Phroute\HandlerResolverInterface;

/**
 * Phroute Dispatcher
 *
 * A simple implementation of dispatcher that is built on top of Phroute.
 *
 * https://github.com/mrjgreen/phroute
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PhrouteDispatcher implements DispatcherInterface
{
    /**
     * @var \Phroute\Phroute\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Phroute\Phroute\HandlerResolverInterface|null
     */
    protected $resolver;

    /**
     * @var \Rougin\Slytherin\Routing\RouterInterface
     */
    protected $router;

    /**
     * Initializes the dispatcher instance.
     *
     * @param \Rougin\Slytherin\Routing\RouterInterface|null $router
     * @param \Phroute\Phroute\HandlerResolverInterface|null $resolver
     */
    public function __construct(RouterInterface $router = null, HandlerResolverInterface $resolver = null)
    {
        if ($resolver) $this->resolver = $resolver;

        if ($router) $this->router($router);
    }

    /**
     * Dispatches against the provided HTTP method verb and URI.
     *
     * @param  string $httpMethod
     * @param  string $uri
     * @return array|mixed
     */
    public function dispatch($httpMethod, $uri)
    {
        $result = array();

        try
        {
            $this->allowed((string) $httpMethod);

            $result = $this->dispatcher->dispatch($httpMethod, $uri);

            $middlewares = array();

            if ($result)
            {
                $route = $this->router->retrieve($httpMethod, $uri);

                $middlewares = $route->getMiddlewares();
            }

            $result = array($result, $middlewares);
        }
        catch (\Exception $exception)
        {
            $this->exceptions($exception, $uri);
        }

        return $result;
    }

    /**
     * Sets the router and parse its available routes if needed.
     *
     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
     * @return self
     */
    public function router(RouterInterface $router)
    {
        $phroute = new PhrouteRouter;

        $phroute->parseRoutes($router->routes());

        $routes = $phroute->getParsed();

        $this->router = $router;

        $this->dispatcher = new Phroute($routes, $this->resolver);

        return $this;
    }

    /**
     * Returns exceptions based on catched error.
     *
     * @throws \UnexpectedValueException
     *
     * @param  \Exception $exception
     * @param  string     $uri
     * @return void
     */
    protected function exceptions(\Exception $exception, $uri)
    {
        $interface = 'Phroute\Phroute\Exception\HttpRouteNotFoundException';

        $message = $exception->getMessage();

        if (is_a($exception, $interface))
        {
            $message = 'Route "' . $uri . '" not found';
        }

        throw new \UnexpectedValueException($message);
    }

    /**
     * Checks if the specified method is a valid HTTP method.
     *
     * @param  string $httpMethod
     * @return boolean
     *
     * @throws \UnexpectedValueException
     */
    protected function allowed($httpMethod)
    {
        $allowed = array('DELETE', 'GET', 'OPTIONS', 'PATCH', 'POST', 'PUT');

        if (! in_array($httpMethod, $allowed))
        {
            $message = 'Used method is not allowed';

            throw new \UnexpectedValueException($message);
        }

        return true;
    }
}

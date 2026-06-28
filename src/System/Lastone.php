<?php

namespace Rougin\Slytherin\System;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\HandlerInterface;
use Rougin\Slytherin\Middleware\Handler as MiddlewareHandler;
use Rougin\Slytherin\Middleware\Resolver;
use Rougin\Slytherin\Routing\DispatcherInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Lastone implements HandlerInterface
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Routing\DispatcherInterface  $dispatcher
     */
    public function __construct(ContainerInterface $container, DispatcherInterface $dispatcher)
    {
        $this->container = $container;

        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();

        $method = $request->getMethod();

        $route = $this->dispatcher->dispatch($method, $uri);

        $handler = new Handler($route, $this->container);

        $items = $route->getMiddlewares();

        if (empty($items))
        {
            return $handler->handle($request);
        }

        $resolver = new Resolver(new Response);

        $stack = array();

        foreach ($items as $item)
        {
            $stack[] = $resolver->resolve($item);
        }

        $wrapper = new MiddlewareHandler($stack, $handler);

        return $wrapper->handle($request);
    }
}

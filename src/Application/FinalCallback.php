<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Routing\RouteInterface;

/**
 * Final Callback
 *
 * Builds the final callback after handling from application.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FinalCallback
{
    const REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @var \Rougin\Slytherin\Routing\RouteInterface
     */
    protected $route;

    /**
     * Sets up the callback handler.
     *
     * @param \Rougin\Slytherin\Routing\RouteInterface $route
     * @param \Rougin\Slytherin\Container\Container    $container
     */
    public function __construct(RouteInterface $route, Container $container)
    {
        $this->container = $container;

        $this->route = $route;
    }

    /**
     * Returns a callback for handling the application.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $handler = $this->route->getHandler();

        // Attach the request again in the container to reflect from stack ---
        $this->container->set(self::REQUEST, $request);
        // -------------------------------------------------------------------

        if (is_array($handler) && is_string($handler[0]))
        {
            $handler[0] = $this->container->resolve($handler[0], $request);

            /** @var object|string */
            $objectOrMethod = $handler[0];

            /** @var string */
            $method = $handler[1];

            $reflector = new \ReflectionMethod($objectOrMethod, $method);
        }
        else
        {
            /** @var \Closure|string */
            $closure = $handler;

            $reflector = new \ReflectionFunction($closure);
        }

        /** @var callable */
        $callable = $handler;

        $parameters = $this->route->getParams();

        $parameters = $this->container->arguments($reflector, $parameters);

        $handler = call_user_func_array($callable, $parameters);

        return $this->finalize($handler);
    }

    /**
     * Converts the result into a \Psr\Http\Message\ResponseInterface instance.
     *
     * @param  \Psr\Http\Message\ResponseInterface|mixed $function
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function finalize($function)
    {
        /** @var \Psr\Http\Message\ResponseInterface */
        $response = $this->container->get(self::RESPONSE);

        if (is_string($function))
        {
            $stream = $response->getBody();

            $stream->write($function);
        }

        $instanceof = $function instanceof ResponseInterface;

        return $instanceof ? $function : $response;
    }
}

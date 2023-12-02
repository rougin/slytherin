<?php

namespace Rougin\Slytherin\System;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Routing\RouteInterface;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\System;

/**
 * Builds the final callback after handling from application.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Handler implements HandlerInterface
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Rougin\Slytherin\Routing\RouteInterface
     */
    protected $route;

    /**
     * Initializes the system handler.
     *
     * @param \Rougin\Slytherin\Routing\RouteInterface       $route
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     */
    public function __construct(RouteInterface $route, ContainerInterface $container)
    {
        $this->route = $route;

        $this->container = $container;
    }

    /**
     * Returns a callback for handling the application.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        // Attach the request again in the container to reflect from stack ---
        $this->container->set(System::SERVER_REQUEST, $request);
        // -------------------------------------------------------------------

        $resolver = new Resolver($this->container);

        $handler = $this->route->getHandler();

        if (is_array($handler) && is_string($handler[0]))
        {
            $handler[0] = $resolver->resolve($handler[0], $request);

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

        $params = $this->setParams($reflector);

        $handler = call_user_func_array($callable, $params);

        return $this->setResponse($handler);
    }

    /**
     * Parses the reflection parameters against the result parameters.
     *
     * @param  \ReflectionFunctionAbstract $reflector
     * @return array<int, mixed>
     */
    protected function setParams(\ReflectionFunctionAbstract $reflector)
    {
        $resolver = new Resolver($this->container);

        $params = $this->route->getParams();

        if (empty($params))
        {
            return $resolver->getArguments($reflector, $params);
        }

        $items = $reflector->getParameters();

        $values = $resolver->getArguments($reflector, $params);

        $result = array();

        foreach (array_keys($items) as $key)
        {
            $exists = array_key_exists($key, $values);

            $result[] = $exists ? $values[$key] : $params[$key];
        }

        return $result;
    }

    /**
     * Converts the result into a \Psr\Http\Message\ResponseInterface instance.
     *
     * @param  \Psr\Http\Message\ResponseInterface|mixed $function
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function setResponse($function)
    {
        /** @var \Psr\Http\Message\ResponseInterface */
        $response = $this->container->get(System::RESPONSE);

        if (is_string($function))
        {
            $stream = $response->getBody();

            $stream->write($function);
        }

        $instanceof = $function instanceof ResponseInterface;

        return $instanceof ? $function : $response;
    }
}

<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;

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
     * @var \Psr\Http\Message\ResponseInterface|mixed
     */
    protected $function;

    /**
     * Sets up the callback handler.
     *
     * @param \Rougin\Slytherin\Container\Container     $container
     * @param \Psr\Http\Message\ResponseInterface|mixed $function
     */
    public function __construct(Container $container, $function)
    {
        $this->container = $container;

        $this->function = $function;
    }

    /**
     * Returns a callback for handling the application.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        if (! is_array($this->function))
        {
            return $this->finalize($this->function);
        }

        // Attach the request again in the container to reflect from stack ---
        $this->container->set(self::REQUEST, $request);
        // -------------------------------------------------------------------

        /** @var array<int, \Closure|string|array<int, string>> */
        $function = $this->function;

        if (is_array($function[0]) && is_string($function[0][0]))
        {
            $function[0][0] = $this->container->resolve($function[0][0], $request);

            /** @var object|string */
            $objectOrMethod = $function[0][0];

            /** @var string */
            $method = $function[0][1];

            $reflector = new \ReflectionMethod($objectOrMethod, $method);
        }
        else
        {
            /** @var \Closure|string */
            $closure = $function[0];

            $reflector = new \ReflectionFunction($closure);
        }

        /** @var callable */
        $callable = $function[0];

        /** @var array<string, mixed> */
        $parameters = $function[1];

        $parameters = $this->container->arguments($reflector, $parameters);

        $this->function = call_user_func_array($callable, $parameters);

        return $this->finalize($this->function);
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
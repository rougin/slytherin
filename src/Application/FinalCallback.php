<?php

namespace Rougin\Slytherin\Application;

/**
 * Final Callback
 *
 * Builds the final callback after handling from application.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class FinalCallback
{
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
    public function __construct(\Rougin\Slytherin\Container\Container $container, $function)
    {
        $this->container = $container;

        $this->function = $function;
    }

    /**
     * Returns a callback for handling the application.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return callback
     */
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request)
    {
        if (is_array($this->function) === true) {
            $function = $this->function;

            if (is_array($function[0]) && is_string($function[0][0])) {
                $function[0][0] = $this->container->resolve($function[0][0], $request);

                $reflector = new \ReflectionMethod($function[0][0], $function[0][1]);
            } else {
                $reflector = new \ReflectionFunction($function[0]);
            }

            $function[1] = $this->container->arguments($reflector, $function[1]);

            $this->function = call_user_func_array($function[0], $function[1]);
        }

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
        $original = $this->container->get(self::RESPONSE);

        $response = (! is_string($function)) ? $function : $original;

        is_string($function) && $response->getBody()->write($function);

        return $response;
    }
}

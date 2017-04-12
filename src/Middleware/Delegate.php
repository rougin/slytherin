<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Delegate
 *
 * Calls the callback with a specified HTTP request.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class Delegate implements \Interop\Http\ServerMiddleware\DelegateInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback = null)
    {
        $this->callback = $callback ?: function () {};
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(\Psr\Http\Message\ServerRequestInterface $request)
    {
        return call_user_func($this->callback, $request);
    }
    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request)
    {
        return $this->process($request);
    }
}

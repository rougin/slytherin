<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

use Psr\Http\Message\ServerRequestInterface;

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
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        return call_user_func($this->callback, $request);
    }
    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->process($request);
    }
}

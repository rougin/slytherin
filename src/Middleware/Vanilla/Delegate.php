<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

/**
 * Delegate
 *
 * Calls the callback with a specified HTTP request.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Delegate
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
     * Calls the specified callback with the HTTP request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request)
    {
        return call_user_func($this->callback, $request);
    }
}

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
     * @var array
     */
    protected $queue = array();

    /**
     * @param callable $callback
     * @param array    $queue
     */
    public function __construct($callback, array $queue)
    {
        $this->callback = $callback;
        $this->queue    = $queue;
    }

    /**
     * Calls the specified callback with the HTTP request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return mixed
     */
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request)
    {
        return call_user_func_array($this->callback, array($request, $this->queue));
    }
}

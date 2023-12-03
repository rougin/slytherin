<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Converts callables into Slytherin middlewares.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Callback
{
    /**
     * @var callable
     */
    protected $middleware;

    /**
     * @var \Psr\Http\Message\ResponseInterface|null
     */
    protected $response = null;

    /**
     * Initializes the middleware instance.
     *
     * @param callable                                 $middleware
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct($middleware, ResponseInterface $response = null)
    {
        $this->middleware = $middleware;

        $this->response = $response;
    }

    /**
     * Processes an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  mixed                                    $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, $handler)
    {
        $middleware = $this->middleware;

        return $middleware($request, $handler);

        // TODO: Allow only double pass callable middlewares ---
        // $fn = function ($request) use ($delegate)
        // {
        //     return $delegate->process($request);
        // };

        // return $middleware($request, $this->response, $fn);
        // -----------------------------------------------------
    }
}

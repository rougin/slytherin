<?php

namespace Rougin\Slytherin\Server;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Converts callables into PSR-15 middlewares.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @author  Rasmus Schultz <rasmus@mindplay.dk>
 */
class Wrapper
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
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $middleware = $this->middleware;

        return $middleware($request, $delegate);

        // TODO: Allow only double pass callable middlewares ---
        // $fn = function ($request) use ($delegate)
        // {
        //     return $delegate->process($request);
        // };

        // return $middleware($request, $this->response, $fn);
        // -----------------------------------------------------
    }
}

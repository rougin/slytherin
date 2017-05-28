<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\ServerMiddleware\DelegateInterface;

/**
 * Final Response
 *
 * Acts as the last in the stack in the list of defined middlewares.
 * NOTE: To be removed in v1.0.0. The final middleware will be the core function.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class FinalResponse implements \Interop\Http\ServerMiddleware\MiddlewareInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct(ResponseInterface $response = null)
    {
        $this->response = $response ?: new \Rougin\Slytherin\Http\Response;
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface      $request
     * @param  \Psr\Http\Message\ServerRequestInterface $response
     * @param  callable|null                            $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        return $response;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return $this->response;
    }
}

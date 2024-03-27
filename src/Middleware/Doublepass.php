<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Doublepass
 *
 * A backward compatible handler to double-pass middlewares.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @codeCoverageIgnore
 */
class Doublepass implements HandlerInterface
{
    /**
     * @var callable
     */
    protected $handler;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param callable                            $handler
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct($handler, ResponseInterface $response)
    {
        $this->handler = $handler;

        $this->response = $response;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->handle($request);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        return call_user_func($this->handler, $request, $this->response);
    }
}

<?php

namespace Rougin\Slytherin\Middleware\Handlers;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\HandlerInterface;

/**
 * PSR-15 Handler (v0.5.0)
 *
 * A handler that is backward compatible for the v0.5.0 version of "http-interop".
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 *
 * @codeCoverageIgnore
 */
class Handler050 implements RequestHandlerInterface, HandlerInterface
{
    /**
     * @var \Rougin\Slytherin\Middleware\HandlerInterface
     */
    protected $handler;

    /**
     * @param \Rougin\Slytherin\Middleware\HandlerInterface $handler
     */
    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
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
        return $this->handler->handle($request);
    }
}

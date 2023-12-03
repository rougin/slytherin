<?php

namespace Rougin\Slytherin\Server\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\HandlerInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Handler100 implements RequestHandlerInterface
{
    /**
     * @var \Rougin\Slytherin\Server\HandlerInterface
     */
    protected $handler;

    /**
     * @param \Rougin\Slytherin\Server\HandlerInterface $handler
     */
    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}

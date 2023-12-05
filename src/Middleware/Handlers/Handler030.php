<?php

namespace Rougin\Slytherin\Middleware\Handlers;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\HandlerInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Handler030 implements DelegateInterface
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
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->process($request);
    }

    /**
     * @param  \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(RequestInterface $request)
    {
        return $this->handler->handle($request);
    }
}

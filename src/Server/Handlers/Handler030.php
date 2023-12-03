<?php

namespace Rougin\Slytherin\Server\Handlers;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Rougin\Slytherin\Server\HandlerInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Handler030 implements DelegateInterface
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
     * @param  \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(RequestInterface $request)
    {
        return $this->handler->handle($request);
    }
}

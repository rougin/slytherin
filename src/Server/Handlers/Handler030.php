<?php

namespace Rougin\Slytherin\Server\Handlers;

use Interop\Http\Middleware\DelegateInterface;
use Psr\Http\Message\RequestInterface;
use Rougin\Slytherin\Server\Handler;

class Handler030 implements DelegateInterface
{
    /**
     * @var \Rougin\Slytherin\Server\Handler
     */
    protected $handler;

    /**
     * @param \Rougin\Slytherin\Server\Handler $handler
     */
    public function __construct(Handler $handler)
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
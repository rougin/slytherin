<?php

namespace Rougin\Slytherin\Server\Handlers;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\Handler;

class Handler050 implements RequestHandlerInterface
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
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        return $this->handler->handle($request);
    }
}

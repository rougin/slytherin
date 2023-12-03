<?php

namespace Rougin\Slytherin\Server\Handlers;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\Handler;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Handler041 implements DelegateInterface
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
    public function process(ServerRequestInterface $request)
    {
        return $this->handler->handle($request);
    }
}

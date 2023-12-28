<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Middleware\Handlers;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\HandlerInterface;

/**
 * PSR-15 Handler (v0.4.1)
 *
 * A handler that is backward compatible for the v0.4.1 version of "http-interop".
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Handler041 implements DelegateInterface
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
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        return $this->handler->handle($request);
    }
}

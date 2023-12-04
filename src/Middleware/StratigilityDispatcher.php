<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\Server\Interop;
use Zend\Stratigility\MiddlewarePipe;

/**
 * Stratigility Dispatcher
 *
 * A simple implementation of middleware dispatcher that is built on top of Zend's Stratigility.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @link    https://github.com/zendframework/zend-stratigility
 */
class StratigilityDispatcher extends Dispatcher
{
    /**
     * @var \Zend\Stratigility\MiddlewarePipe
     */
    protected $zend;

    /**
     * @param \Zend\Stratigility\MiddlewarePipe $pipe
     * @param mixed[]                           $stack
     */
    public function __construct(MiddlewarePipe $pipe, $stack = array())
    {
        parent::__construct($stack);

        $this->zend = $pipe;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Rougin\Slytherin\Server\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    // public function process(ServerRequestInterface $request, HandlerInterface $handler)
    // {
    //     foreach ($this->getStack() as $item)
    //     {
    //         $this->zend->pipe($item);
    //     }

    //     $next = Interop::get($handler);

    //     return $this->zend->process($request, $next);
    // }
}

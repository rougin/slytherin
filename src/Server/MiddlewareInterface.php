<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface MiddlewareInterface
{
    /**
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Rougin\Slytherin\Server\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler);
}

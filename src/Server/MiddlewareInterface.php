<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
interface MiddlewareInterface
{
    public function getStack();

    public function process(ServerRequestInterface $request, HandlerInterface $handler);

    public function setStack($stack);
}

<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class Endofline implements HandlerInterface
{
    public function handle(ServerRequestInterface $request)
    {
        return new Response;
    }
}
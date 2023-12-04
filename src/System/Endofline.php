<?php

namespace Rougin\Slytherin\System;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Server\HandlerInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Endofline implements HandlerInterface
{
    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        return new Response;
    }
}

<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Final Response
 *
 * Acts as the last in the stack in the list of defined middlewares.
 * NOTE: To be removed in v1.0.0. The final middleware will be the core function.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class FinalResponse
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface $request
     * @param  callable|null                       $next
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, $next = null)
    {
        return new \Rougin\Slytherin\Http\Response;
    }
}

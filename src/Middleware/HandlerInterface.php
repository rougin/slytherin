<?php

namespace Rougin\Slytherin\Middleware;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Handler Interface
 *
 * An interface for handling delegate instances.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface HandlerInterface
{
    /**
     * Dispatches the next available middleware and return the response.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request);
}

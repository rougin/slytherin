<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\HandlerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * @var string[]
     */
    protected $allowed = array('*');

    /**
     * @var string[]
     */
    protected $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS');

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface      $request
     * @param  \Rougin\Slytherin\Middleware\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $isOptions = $request->getMethod() === 'OPTIONS';

        $response = $isOptions ? new Response : $handler->handle($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', $this->allowed);

        $response = $response->withHeader('Access-Control-Allow-Methods', $this->methods);

        return $response;
    }
}

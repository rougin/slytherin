<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\HandlerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * Body Parameters Middleware
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class BodyParametersMiddleware implements MiddlewareInterface
{
    /**
     * @var array
     */
    protected $complex = array('PUT', 'DELETE');

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface      $request
     * @param  \Rougin\Slytherin\Middleware\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $handler)
    {
        if (in_array($request->getMethod(), $this->complex)) {
            parse_str(file_get_contents('php://input'), $body);

            $request = $request->withParsedBody((array) $body);
        }

        return $handler->{HANDLER_METHOD}($request);
    }
}

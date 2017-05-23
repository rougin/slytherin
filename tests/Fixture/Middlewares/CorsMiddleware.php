<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Interop\Http\ServerMiddleware\DelegateInterface;

/**
 * CORS Middleware
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CorsMiddleware implements \Interop\Http\ServerMiddleware\MiddlewareInterface
{
    /**
     * @var array
     */
    protected $complex = array('DELETE', 'PUT');

    /**
     * @var array
     */
    protected $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS');

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface         $request
     * @param  \Interop\Http\ServerMiddleware\DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        // TODO: This must be used instead in HttpIntegration:86 :(
        if (in_array($request->getMethod(), $this->complex)) {
            parse_str(file_get_contents('php://input'), $body);

            $request = $request->withParsedBody($body);
        }

        $response = $delegate->process($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', '*');

        return $response->withHeader('Access-Control-Allow-Methods', $this->methods);
    }
}

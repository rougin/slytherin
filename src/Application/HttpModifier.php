<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * HTTP Modifier
 *
 * Modifies the HTTP by updating the HTTP response with middleware (if included).
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HttpModifier
{
    /**
     * @var \Rougin\Slytherin\Middleware\MiddlewareInterface|null
     */
    protected $middleware = null;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Checks if previous response is available.
     *
     * @param  \Psr\Http\Message\ResponseInterface|string      $final
     * @param  \Psr\Http\Message\ResponseInterface|string|null $first
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse($final, $first = null)
    {
        $response = $this->response;

        if ($first instanceof ResponseInterface) {
            $response = $this->setHeaders($first);
        }

        if ($final instanceof ResponseInterface) {
            $response = $this->setHeaders($final);
        } else {
            $response->getBody()->write($final);
        }

        return $response;
    }

    /**
     * Sets the HTTP middleware.
     *
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @return self
     */
    public function setMiddleware(\Rougin\Slytherin\Middleware\MiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * Sets the defined middlewares to the HTTP response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  array                                    $middlewares
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function invokeMiddleware(ServerRequestInterface $request, array $middlewares = array())
    {
        if (is_a($this->middleware, 'Rougin\Slytherin\Middleware\MiddlewareInterface')) {
            $middlewares = array_merge($this->middleware->getStack(), $middlewares);
        }

        if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            array_push($middlewares, new \Rougin\Slytherin\Middleware\FinalResponse($this->response));
        }

        $middleware = $this->middleware;

        return $middleware($request, $this->response, $middlewares);
    }

    /**
     * Creates the HTTP headers and inject it to the HTTP response.
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function setHeaders(ResponseInterface $response)
    {
        $protocol = 'HTTP/' . $response->getProtocolVersion();
        $httpCode = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        header($protocol . ' ' . $httpCode);

        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        return $response;
    }
}

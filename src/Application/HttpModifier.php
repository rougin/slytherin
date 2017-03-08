<?php

namespace Rougin\Slytherin\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Middleware\MiddlewareInterface;

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
    const MIDDLEWARE = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

    /**
     * @var \Rougin\Slytherin\Middleware\MiddlewareInterface|null
     */
    protected $middleware = null;

    /**
     * @var array
     */
    protected $middlewares = array();

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

        if (is_a($first, 'Psr\Http\Message\ResponseInterface')) {
            $response = $first;
        }

        if (is_a($final, 'Psr\Http\Message\ResponseInterface')) {
            $response = $final;
        } else {
            $response->getBody()->write($final);
        }

        return $this->setHeaders($response);
    }

    /**
     * Creates the HTTP headers and inject it to the HTTP response.
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setHeaders(ResponseInterface $response)
    {
        $protocol = 'HTTP/' . $response->getProtocolVersion();
        $httpCode = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        header($protocol . ' ' . $httpCode);

        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        return $response;
    }

    /**
     * Sets the HTTP middleware.
     *
     * @param  Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @return self
     */
    public function setMiddleware(MiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * Gets defined middlewares and put it to the stack.
     *
     * @param  array $middlewares
     * @return self
     */
    public function setMiddlewareStack(array $middlewares = array())
    {
        $this->middlewares = $middlewares;

        if (is_a($this->middleware, self::MIDDLEWARE)) {
            $this->middlewares = array_merge($this->middleware->getQueue(), $this->middlewares);
        }

        if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            array_push($this->middlewares, new \Rougin\Slytherin\Middleware\FinalResponse($this->response));
        }

        return $this;
    }

    /**
     * Sets the defined middlewares to the HTTP response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function invokeMiddleware(ServerRequestInterface $request)
    {
        $middleware = $this->middleware;

        return $middleware($request, $this->response, $this->middlewares);
    }
}

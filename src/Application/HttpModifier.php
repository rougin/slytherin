<?php

namespace Rougin\Slytherin\Application;

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
    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Checks if previous response is available.
     *
     * @param  \Psr\Http\Message\ResponseInterface|mixed $final
     * @param  \Psr\Http\Message\ResponseInterface|mixed $first
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response($final, $first = null)
    {
        $response = $this->response;

        if (is_a($first, 'Psr\Http\Message\ResponseInterface')) {
            $response = $this->headers($first);
        }

        if (is_a($final, 'Psr\Http\Message\ResponseInterface')) {
            return $this->headers($final);
        }

        $response->getBody()->write($final);

        return $response;
    }

    /**
     * Sets the HTTP middleware.
     *
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @return self
     */
    public function middleware(\Rougin\Slytherin\Middleware\MiddlewareInterface $middleware)
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
    public function invoke(\Psr\Http\Message\ServerRequestInterface $request, array $middlewares = array())
    {
        $middlewares = array_merge($this->middleware->stack(), $middlewares);

        if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $response = new \Rougin\Slytherin\Middleware\FinalResponse($this->response);

            array_push($middlewares, $response);
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
    protected function headers(\Psr\Http\Message\ResponseInterface $response)
    {
        $code = $response->getStatusCode() . ' ' . $response->getReasonPhrase();

        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        header('HTTP/' . $response->getProtocolVersion() . ' ' . $code);

        return $response;
    }
}

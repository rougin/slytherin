<?php

namespace Rougin\Slytherin\Application\Traits;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Rougin\Slytherin\Middleware\MiddlewareInterface as Middleware;

trait PrepareMiddlewaresTrait
{
    /**
     * Sets the response to the user.
     *
     * @param  mixed                               $result
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    abstract protected function prepareHttpResponse($result, Response $response);

    /**
     * Prepares the defined middlewares.
     *
     * @param  array $middlewares
     * @return mixed
     */
    private function prepareMiddlewares(Middleware $middleware = null, Request $request, Response $response, array $middlewares = [])
    {
        $result = null;

        if ($middleware && ! empty($middlewares)) {
            $result = $middleware($request, $response, $middlewares);
        }

        return ($result) ? $this->prepareHttpResponse($result, $response) : null;
    }
}

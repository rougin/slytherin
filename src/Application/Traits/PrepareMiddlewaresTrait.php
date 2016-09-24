<?php

namespace Rougin\Slytherin\Application\Traits;

use Psr\Http\Message\ResponseInterface;

use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * Prepare Middleware Trait
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait PrepareMiddlewaresTrait
{
    /**
     * Sets the response to the user.
     *
     * @param  mixed                               $result
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    abstract protected function prepareHttpResponse($result, ResponseInterface $response);

    /**
     * Prepares the defined middlewares.
     *
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @param  array                                            $middlewares
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    private function prepareMiddlewares(MiddlewareInterface $middleware = null, array $middlewares = [])
    {
        $request  = $this->components->getHttpRequest();
        $response = $this->components->getHttpResponse();
        $result   = null;

        if ($middleware && ! empty($middlewares)) {
            $result = $middleware($request, $response, $middlewares);
        }

        return ($result) ? $this->prepareHttpResponse($result, $response) : null;
    }
}

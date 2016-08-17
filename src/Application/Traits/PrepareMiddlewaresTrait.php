<?php

namespace Rougin\Slytherin\Application\Traits;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Middleware\MiddlewareInterface;

trait PrepareMiddlewaresTrait
{
    /**
     * Sets the response to the user.
     * 
     * @param  mixed $result
     * @return \Psr\Http\Message\ResponseInterface
     */
    abstract protected function prepareHttpResponse($result);

    /**
     * Prepares the defined middlewares.
     * 
     * @param  array $middlewares
     * @return mixed
     */
    private function prepareMiddlewares(MiddlewareInterface $middleware = null, ServerRequestInterface $request, ResponseInterface $response, array $middlewares = [])
    {
        $result = null;

        if ($middleware && ! empty($middlewares)) {
            $result = $middleware($request, $response, $middlewares);
        }

        return ($result) ? $this->prepareHttpResponse($result) : null;
    }
}

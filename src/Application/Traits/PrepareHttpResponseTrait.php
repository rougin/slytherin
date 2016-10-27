<?php

namespace Rougin\Slytherin\Application\Traits;

use Psr\Http\Message\ResponseInterface;

/**
 * Prepare HTTP Response Trait
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
trait PrepareHttpResponseTrait
{
    /**
     * Sets the response to the user.
     *
     * @param  mixed                               $result
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function prepareHttpResponse($result, ResponseInterface $response)
    {
        if (! $result instanceof ResponseInterface) {
            $response->getBody()->write($result);
        } else {
            $response = $result;
        }

        // Sets the specified headers, if any.
        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        // Sets the HTTP response code.
        http_response_code($response->getStatusCode());

        return $response;
    }
}

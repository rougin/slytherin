<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class LastMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next = null)
    {
        if ($response->getBody() == '')
        {
            $response->getBody()->write('Loaded with middleware');
        }
        else
        {
            $response->getBody()->write(' Last!');
        }

        return $response;
    }
}

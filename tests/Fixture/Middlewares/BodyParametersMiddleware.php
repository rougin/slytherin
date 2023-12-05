<?php

namespace Rougin\Slytherin\Fixture\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Middleware\HandlerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class BodyParametersMiddleware implements MiddlewareInterface
{
    protected $complex = array('PUT', 'DELETE');

    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        if (! in_array($request->getMethod(), $this->complex))
        {
            return $handler->handle($request);
        }

        $file = file_get_contents('php://input');

        parse_str($file, $body);

        return $request->withParsedBody($body);
    }
}

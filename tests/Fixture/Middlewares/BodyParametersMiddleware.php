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
    /**
     * @var string[]
     */
    protected $complex = array('PUT', 'DELETE');

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface      $request
     * @param  \Rougin\Slytherin\Middleware\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        if (! in_array($request->getMethod(), $this->complex))
        {
            return $handler->handle($request);
        }

        /** @var string */
        $file = file_get_contents('php://input');

        parse_str($file, $body);

        $request = $request->withParsedBody($body);

        return $handler->handle($request);
    }
}

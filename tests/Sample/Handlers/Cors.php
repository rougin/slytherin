<?php

namespace Rougin\Slytherin\Sample\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Server\HandlerInterface;
use Rougin\Slytherin\Server\MiddlewareInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Cors implements MiddlewareInterface
{
    const METHODS = 'Access-Control-Allow-Methods';

    const ORIGIN = 'Access-Control-Allow-Origin';

    protected $allowed = array();

    protected $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS');

    public function __construct(array $allowed = null, array $methods = null)
    {
        if (! $allowed) $allowed = array('*');

        if (! $methods) $methods = $this->methods;

        $this->allowed = $allowed;

        $this->methods = $methods;
    }

    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $options = $request->getMethod() === 'OPTIONS';

        $response = $options ? new Response : $handler->handle($request);

        $response = $response->withHeader(self::ORIGIN, $this->allowed);

        return $response->withHeader(self::METHODS, (array) $this->methods);
    }
}

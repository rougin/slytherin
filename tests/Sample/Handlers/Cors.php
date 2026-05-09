<?php

namespace Rougin\Slytherin\Sample\Handlers;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Middleware\HandlerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Cors implements MiddlewareInterface
{
    const METHODS = 'Access-Control-Allow-Methods';

    const ORIGIN = 'Access-Control-Allow-Origin';

    /**
     * @var string[]
     */
    protected $allowed = array();

    /**
     * @var string[]
     */
    protected $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS');

    /**
     * @param string[] $allowed
     * @param string[] $methods
     */
    public function __construct($allowed = array(), $methods = array())
    {
        if (count($allowed) === 0)
        {
            $allowed = array('*');
        }

        if (count($methods) === 0)
        {
            $methods = $this->methods;
        }

        $this->allowed = $allowed;

        $this->methods = $methods;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface      $request
     * @param \Rougin\Slytherin\Middleware\HandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $response = new Response;

        if ($request->getMethod() !== 'OPTIONS')
        {
            $response = $handler->handle($request);
        }

        $response = $response->withHeader(self::ORIGIN, $this->allowed);

        return $response->withHeader(self::METHODS, $this->methods);
    }
}

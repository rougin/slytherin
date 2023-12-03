<?php

namespace Rougin\Slytherin\Server;

use Rougin\Slytherin\Server\Handlers\Handler030;
use Rougin\Slytherin\Server\Handlers\Handler041;
use Rougin\Slytherin\Server\Handlers\Handler050;
use Rougin\Slytherin\Server\Handlers\Handler100;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Converts various middlewares into Slytherin counterparts.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Wrapper implements MiddlewareInterface
{
    /**
     * @var mixed
     */
    protected $middleware;

    /**
     * Initializes the middleware instance.
     *
     * @param mixed $middleware
     */
    public function __construct($middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Processes an incoming server request and return a response.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Rougin\Slytherin\Server\HandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, HandlerInterface $handler)
    {
        $middleware = $this->middleware;

        if (is_string($middleware))
        {
            $middleware = new $middleware;
        }

        // @codeCoverageIgnoreStart
        switch (Version::get())
        {
            case '0.3.0':
                $next = new Handler030($handler);

                break;
            case '0.4.1':
                $next = new Handler041($handler);

                break;
            case '0.5.0':
                $next = new Handler050($handler);

                break;
            default:
                $next = new Handler100($handler);

                break;
        }
        // @codeCoverageIgnoreEnd

        /** @phpstan-ignore-next-line */
        return $middleware->process($request, $next);
    }
}

<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\Handlers\Handler030;
use Rougin\Slytherin\Server\Handlers\Handler041;
use Rougin\Slytherin\Server\Handlers\Handler050;
use Rougin\Slytherin\Server\Handlers\Handler100;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Interop implements HandlerInterface
{
    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @param mixed $handler
     */
    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request)
    {
        return $this->handle($request);
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $handler = $this->handler;

        return $handler($request);
    }

    /**
     * @param  mixed $handler
     * @return mixed
     */
    public static function getHandler($handler)
    {
        switch (Version::get())
        {
            case '0.3.0':
                $handler = new Handler030($handler);

                break;
            case '0.4.1':
                $handler = new Handler041($handler);

                break;
            case '0.5.0':
                $handler = new Handler050($handler);

                break;
            case '1.0.0':
                $handler = new Handler100($handler);

                break;
        }

        return $handler;
    }
}

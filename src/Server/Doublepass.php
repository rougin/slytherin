<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * TODO: Add unit test and apply this class.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Doublepass
{
    protected $handler;

    protected $response;

    public function __construct($handler, ResponseInterface $response)
    {
        $this->handler = $handler;

        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request)
    {
        return call_user_func($this->handler, $request, $this->response);
    }
}

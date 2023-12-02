<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

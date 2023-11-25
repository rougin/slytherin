<?php

namespace Rougin\Slytherin\Forward\Fixture\Depots;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class TestDepot
{
    protected $request;

    protected $response;

    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;

        $this->response = $response;
    }

    public function text($data)
    {
        $this->response->getBody()->write($data);

        return $this->response;
    }
}
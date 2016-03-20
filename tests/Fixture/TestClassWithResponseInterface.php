<?php

namespace Rougin\Slytherin\Test\Fixture;

use Psr\Http\Message\ResponseInterface;

/**
 * Test Class With Response Interface
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestClassWithResponseInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function index()
    {
        $response = $this->response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        $response->getBody()->write('Hello with response');

        return $response;
    }

    public function error()
    {
        $response = $this->response
            ->withStatus(401)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        $response->getBody()->write('Hello with error response');

        return $response;
    }
}

<?php

namespace Rougin\Slytherin\Test\Fixture\Classes;

use Psr\Http\Message\ResponseInterface;

/**
 * With Response Interface
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WithResponseInterface
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

    /**
     * Returns a response with a text of "Hello with response".
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $response = $this->response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        $response->getBody()->write('Hello with response');

        return $response;
    }

    /**
     * Returns a response with a text of "Hello with error response".
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
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

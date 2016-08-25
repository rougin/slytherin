<?php

namespace Rougin\Slytherin\Test\Fixture\Classes;

use Psr\Http\Message\ResponseInterface;

/**
 * With PUT HTTP Method
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WithPutHttpMethod
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
     * Returns a response with a text of "Hello from PUT HTTP method".
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $response = $this->response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Credentials', 'true');

        $response->getBody()->write('Hello from PUT HTTP method');

        return $response;
    }
}

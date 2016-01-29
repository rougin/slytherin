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
        $this->response->getBody()->write('Hello');

        return $this->response;
    }
}

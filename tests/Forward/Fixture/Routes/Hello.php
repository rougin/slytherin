<?php

namespace Rougin\Slytherin\Forward\Fixture\Routes;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Hello extends Route
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        $this->response->getBody()->write('Hello world!');

        return $this->response;
    }
}
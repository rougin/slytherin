<?php

namespace Rougin\Slytherin\Sample\Routes;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Sample\Depots\TestDepot;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Hello extends Route
{
    public function conts()
    {
        $data = $this->request->getParsedBody();

        return 'Hello, ' . $data['name'] . '!';
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index(TestDepot $test)
    {
        return $test->text('Hello world!');
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function name($name, TestDepot $test)
    {
        return $test->text("Hello, $name!");
    }

    public function param(ServerRequestInterface $request)
    {
        $data = $request->getParsedBody();

        return 'Hello, ' . $data['name'] . '!';
    }

    /**
     * @return string
     */
    public function string()
    {
        return 'This is a simple string.';
    }
}
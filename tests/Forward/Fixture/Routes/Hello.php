<?php

namespace Rougin\Slytherin\Forward\Fixture\Routes;

use Rougin\Slytherin\Forward\Fixture\Depots\TestDepot;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Hello extends Route
{
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
}
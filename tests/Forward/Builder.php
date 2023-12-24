<?php

namespace Rougin\Slytherin\Forward;

use Rougin\Slytherin\Application;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Builder
{
    public function make()
    {
        $app = new Application;

        $app->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');

        return $app;
    }

    public function setUrl($method, $uri)
    {
        $_SERVER['REQUEST_METHOD'] = $method;

        $_SERVER['REQUEST_URI'] = $uri;

        $_SERVER['SERVER_NAME'] = 'localhost';

        $_SERVER['SERVER_PORT'] = '8000';

        return $this;
    }
}

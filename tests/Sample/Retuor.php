<?php

namespace Rougin\Slytherin\Sample;

use Rougin\Slytherin\Routing\Router as Slytherin;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Retuor extends Slytherin
{
    /**
     * @var string
     */
    protected $namespace = 'Rougin\Slytherin\Sample\Routes';

    /**
     * Returns a listing of available routes.
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function routes()
    {
        $this->get('/world', 'Hello@world');

        $this->post('/upload', 'Hello@upload');

        return $this->routes;
    }
}

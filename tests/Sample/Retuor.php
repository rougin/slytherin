<?php

namespace Rougin\Slytherin\Sample;

use Rougin\Slytherin\Routing\Router;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Retuor extends Router
{
    protected $namespace = 'Rougin\Slytherin\Sample\Routes';

    public function routes($parsed = false)
    {
        $this->get('/world', 'Hello@world');

        return parent::routes($parsed);
    }
}
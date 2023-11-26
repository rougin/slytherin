<?php

namespace Rougin\Slytherin\Sample;

use Rougin\Slytherin\Routing\Router as Slytherin;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Retuor extends Slytherin
{
    protected $namespace = 'Rougin\Slytherin\Sample\Routes';

    public function routes($parsed = false)
    {
        $this->get('/world', 'Hello@world');

        return parent::routes($parsed);
    }
}
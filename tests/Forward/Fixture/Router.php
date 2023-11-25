<?php

namespace Rougin\Slytherin\Forward\Fixture;

use Rougin\Slytherin\Routing\Router as Slytherin;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Router extends Slytherin
{
    protected $namespace = 'Rougin\Slytherin\Forward\Fixture\Routes\\';

    protected $prefix = '/';

    public function routes($parsed = false)
    {
        // Default routes ------------------
        $this->get('/v1/hello', 'Hello@index');
        $this->get('/', 'Home@index');
        // ---------------------------------

        return parent::routes($parsed);
    }
}
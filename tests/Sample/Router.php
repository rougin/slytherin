<?php

namespace Rougin\Slytherin\Sample;

use Rougin\Slytherin\Routing\Router as Slytherin;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Router extends Slytherin
{
    protected $namespace = 'Rougin\Slytherin\Sample\Routes\\';

    protected $prefix = '/';

    public function routes($parsed = false)
    {
        $this->get('/string', 'Hello@string');

        $this->get('/hello', 'Hello@index');

        $this->get('/response', 'Hello@response');

        $this->get('/hi/:name', 'Hello@name');

        $this->get('without-slash', 'Hello@string');

        $this->get('/', 'Home@index');

        $this->get('/callable', function ()
        {
            return 'Welcome call!';
        });

        $this->get('/param', 'Home@param');

        $this->get('/handler/conts', 'Hello@conts');

        $this->get('/handler/param', 'Hello@param');

        return parent::routes($parsed);
    }
}
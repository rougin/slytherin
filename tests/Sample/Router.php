<?php

namespace Rougin\Slytherin\Sample;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Routing\Router as Slytherin;
use Rougin\Slytherin\Server\HandlerInterface;

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

        $this->get('/hi/{name}', 'Hello@name');

        $this->get('without-slash', 'Hello@string');

        $this->get('/', 'Home@index');

        $fn = function ()
        {
            return 'Welcome call!';
        };

        $this->get('/callable', $fn);

        $fn = function ($name, $age)
        {
            return 'Welcome ' . $name . ', ' . $age . '!';
        };

        $this->get('/call/{name}/{age}', $fn);

        $fn = function ($name)
        {
            return 'Welcome ' . $name . '!';
        };

        $this->get('/call/{name}', $fn);

        $this->get('/param', 'Home@param');

        $this->get('/handler/conts', 'Hello@conts');

        $this->get('/handler/param', 'Hello@param');

        $fn = function (ServerRequestInterface $request, HandlerInterface $handler)
        {
            $response = $handler->handle($request);

            $response->getBody()->write('From callable middleware!');

            return $response;
        };

        $this->get('middleware', 'Hello@response', $fn);

        return parent::routes($parsed);
    }
}

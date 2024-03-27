<?php

namespace Rougin\Slytherin\Sample;

use Rougin\Slytherin\Routing\Router as Slytherin;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Router extends Slytherin
{
    /**
     * @var string
     */
    protected $namespace = 'Rougin\Slytherin\Sample\Routes\\';

    /**
     * @var string
     */
    protected $prefix = '/';

    /**
     * Returns a listing of available routes.
     *
     * @return \Rougin\Slytherin\Routing\RouteInterface[]
     */
    public function routes()
    {
        $this->get('/string', 'Hello@string');

        $this->get('/hello', 'Hello@index');

        $this->get('/response', 'Hello@response');

        $this->get('/hi/:name', 'Hello@name');

        $this->get('without-slash', 'Hello@string');

        $fn = function ($request, $next)
        {
            $response = $next($request);

            $response->getBody()->write('From callable middleware!');

            return $response;
        };

        $this->get('middleware', 'Hello@response', $fn);

        $this->get('/', 'Home@index');

        $this->get('/callable', function ()
        {
            return 'Welcome call!';
        });

        $this->get('/call/{name}/{age}', function ($name, $age)
        {
            return 'Welcome ' . $name . ', ' . $age . '!';
        });

        $this->get('/call/:name', function ($name)
        {
            return 'Welcome ' . $name . '!';
        });

        $this->get('/param', 'Home@param');

        $this->get('/handler/conts', 'Hello@conts');

        $this->get('/handler/param', 'Hello@param');

        $interop = 'Rougin\Slytherin\Sample\Handlers\Interop';

        $this->get('interop', 'Hello@response', $interop);

        $this->get('encoded', 'Hello@encoded');

        return parent::routes();
    }
}

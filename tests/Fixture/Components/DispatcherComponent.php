<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;

/**
 * Dispatcher Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * dispatcher, debugger, http, middleware
     *
     * @var string
     */
    protected $type = 'dispatcher';

    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        $routes = [
            [ 'GET', '/', [ 'Rougin\Slytherin\Test\Fixture\Classes\NewClass', 'index' ] ],
            [ 'GET', '/optional', [ 'Rougin\Slytherin\Test\Fixture\Classes\WithOptionalParameter', 'index' ] ],
            [ 'GET', '/parameter', [ 'Rougin\Slytherin\Test\Fixture\Classes\WithParameter', 'index' ] ],
            [ 'GET', '/hello', [ 'Rougin\Slytherin\Test\Fixture\Classes\WithResponseInterface', 'index' ] ],
            [ 'GET', '/error', [ 'Rougin\Slytherin\Test\Fixture\Classes\WithResponseInterface', 'error' ] ],
            [ 'GET', '/middleware', [ 'Rougin\Slytherin\Test\Fixture\Classes\NewClass', 'index' ], 'Rougin\Slytherin\Test\Fixture\Middlewares\LastMiddleware' ],
            [ 'PUT', '/hello', [ 'Rougin\Slytherin\Test\Fixture\Classes\WithPutHttpMethod', 'index' ] ],
            [ 'GET', '/callback', function () {
                return 'Hello';
            } ],
        ];

        return new Dispatcher(new Router($routes));
    }
}

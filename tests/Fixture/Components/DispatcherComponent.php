<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;

/**
 * Dispatcher Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DispatcherComponent extends \Rougin\Slytherin\Component\AbstractComponent
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
            [ 'GET', '/', [ 'Rougin\Slytherin\Fixture\Classes\NewClass', 'index' ] ],
            [ 'GET', '/optional', [ 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter', 'index' ] ],
            [ 'GET', '/parameter', [ 'Rougin\Slytherin\Fixture\Classes\WithParameter', 'index' ] ],
            [ 'GET', '/hello', [ 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface', 'index' ] ],
            [ 'GET', '/error', [ 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface', 'error' ] ],
            [ 'GET', '/middleware', [ 'Rougin\Slytherin\Fixture\Classes\NewClass', 'index' ], 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware' ],
            [ 'PUT', '/hello', [ 'Rougin\Slytherin\Fixture\Classes\WithPutHttpMethod', 'index' ] ],
            [ 'GET', '/callback', function () {
                return 'Hello';
            } ],
        ];

        return new Dispatcher(new Router($routes));
    }
}

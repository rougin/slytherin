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
     * 
     * @return mixed
     */
    public function get()
    {
        $routes = [
            [ 'GET', '/', [ 'Rougin\Slytherin\Test\Fixture\TestClass', 'index' ], ],
            [ 'GET', '/hello', [ 'Rougin\Slytherin\Test\Fixture\TestClassWithResponseInterface', 'index' ] ],
            [ 'GET', '/error', [ 'Rougin\Slytherin\Test\Fixture\TestClassWithResponseInterface', 'error' ] ],
            [ 'GET', '/middleware', [ 'Rougin\Slytherin\Test\Fixture\TestClass', 'index' ], 'Rougin\Slytherin\Test\Fixture\TestMiddleware', ],
            [ 'PUT', '/hello', [ 'Rougin\Slytherin\Test\Fixture\TestClassWithPutHttpMethod', 'index' ] ],
            [ 'GET', '/callback', function () { return 'Hello'; } ],
        ];

        return new Dispatcher(new Router($routes));
    }
}

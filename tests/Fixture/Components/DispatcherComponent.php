<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;

/**
 * Dispatcher Component
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
        $routes = array(
            array('GET', '/', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index')),
            array('GET', '/optional', array('Rougin\Slytherin\Fixture\Classes\WithOptionalParameter', 'index')),
            array('GET', '/parameter', array('Rougin\Slytherin\Fixture\Classes\WithParameter', 'index')),
            array('GET', '/hello', array('Rougin\Slytherin\Fixture\Classes\WithResponseInterface', 'index')),
            array('GET', '/error', array('Rougin\Slytherin\Fixture\Classes\WithResponseInterface', 'error')),
            array('GET', '/middleware', array('Rougin\Slytherin\Fixture\Classes\NewClass', 'index'), 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware'),
            array('PUT', '/hello', array('Rougin\Slytherin\Fixture\Classes\WithPutHttpMethod', 'index')),
            array('GET', '/callback', function () {
                return 'Hello';
            }),
        );

        return new Dispatcher(new Router($routes));
    }
}

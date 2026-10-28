<?php

namespace Rougin\Slytherin\System;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\Routing\Router;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ScalarTypesTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @return void
     */
    public function test_passed_if_boolean_type_resolved()
    {
        $this->checkIfScalarTypesAllowewd();

        $actual = $this->resolve('GET', '/features/1');

        $this->assertEquals('boolean', $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_float_type_resolved()
    {
        $this->checkIfScalarTypesAllowewd();

        $actual = $this->resolve('GET', '/exchange/rate/10');

        $this->assertEquals('double', $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_integer_type_resolved()
    {
        $this->checkIfScalarTypesAllowewd();

        $actual = $this->resolve('GET', '/users/1');

        $this->assertEquals('integer', $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_string_type_resolved()
    {
        $this->checkIfScalarTypesAllowewd();

        $actual = $this->resolve('GET', '/search/slytherin');

        $this->assertEquals('string', $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    protected function resolve($method, $uri)
    {
        $router = new Router;

        $router->prefix('', 'Rougin\Slytherin\Fixture\Classes');

        $router->get('/features/:enabled', 'WithScalarTypes@enabled');
        $router->get('/exchange/rate/:amount', 'WithScalarTypes@rate');
        $router->get('/users/:id', 'WithScalarTypes@show');
        $router->get('/search/:query', 'WithScalarTypes@search');

        $dispatcher = new Dispatcher($router);

        $this->container->set(System::DISPATCHER, $dispatcher);

        $this->container->set(System::RESPONSE, new Response);

        $server = array();
        $server['REQUEST_METHOD'] = $method;
        $server['REQUEST_URI'] = $uri;
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $system = new System($this->container);

        $response = $system->handle($request);

        return $response->getBody();
    }
}

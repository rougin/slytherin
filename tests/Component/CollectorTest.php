<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Debug\ErrorHandler;
use Rougin\Slytherin\Dispatching\Vanilla\Dispatcher;
use Rougin\Slytherin\Dispatching\Vanilla\Router;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Components\InvalidContainerComponent;
use Rougin\Slytherin\Fixture\Components\InvalidHttpNotArray;
use Rougin\Slytherin\Fixture\Components\InvalidHttpRequest;
use Rougin\Slytherin\Fixture\Components\InvalidHttpResponse;
use Rougin\Slytherin\Fixture\Components\InvalidMiddlewareComponent;
use Rougin\Slytherin\Fixture\Components\InvalidTemplateComponent;
use Rougin\Slytherin\Fixture\Components\SampleComponent;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Middleware\Middleware;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\Renderer;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CollectorTest extends Testcase
{
    const COMPONENT_NOT_ARRAY = 'Rougin\Slytherin\System\Errors\ComponentNotArray';

    const CONTAINER_NOT_FOUND = 'Rougin\Slytherin\System\Errors\ContainerNotFound';

    const MIDDLEWARE_NOT_FOUND = 'Rougin\Slytherin\System\Errors\MiddlewareNotFound';

    const REQUEST_NOT_FOUND = 'Rougin\Slytherin\System\Errors\RequestNotFound';

    const RESPONSE_NOT_FOUND = 'Rougin\Slytherin\System\Errors\ResponseNotFound';

    const TEMPLATE_NOT_FOUND = 'Rougin\Slytherin\System\Errors\TemplateNotFound';

    /**
     * @return void
     */
    public function test_failed_if_component_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $sample = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        new Collector(array($sample));
    }

    /**
     * @return void
     */
    public function test_failed_if_container_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $sample = new SampleComponent('container', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_debugger_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $sample = new SampleComponent('debugger', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_dispatcher_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $sample = new SampleComponent('dispatcher', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_http_component_not_an_array()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $sample = new SampleComponent('http', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_http_request_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $values = array(new NewClass, new Response);

        $sample = new SampleComponent('http', $values);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_http_response_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $server = array('REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'SERVER_NAME' => 'localhost', 'SERVER_PORT' => '8000');

        $values = array(new ServerRequest($server), new NewClass);

        $sample = new SampleComponent('http', $values);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_middleware_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $sample = new SampleComponent('middleware', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_template_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doExpectException($expect);

        $sample = new SampleComponent('template', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_passed_if_collected_with_custom_container()
    {
        $item = 'Rougin\Slytherin\Fixture\Components\MiddlewareComponent';

        // Create a custom container with an entry ---
        $container = new Container;

        $container->set('test', new NewClass);
        // -------------------------------------------

        $collection = Collector::get(array($item), $container);

        // Verify the custom container was used ---
        $result = $collection->getContainer();

        $this->assertTrue($result->has('test'));
        // ----------------------------------------

        $this->assertTrue($collection->has(System::MIDDLEWARE));
    }

    /**
     * @return void
     */
    public function test_passed_if_collected_without_container()
    {
        $item = 'Rougin\Slytherin\Fixture\Components\MiddlewareComponent';

        $collection = Collector::get(array($item));

        $this->assertTrue($collection->has(System::MIDDLEWARE));
    }

    /**
     * @return void
     */
    public function test_passed_if_container_sample_valid()
    {
        $container = new Container;

        $sample = new SampleComponent('container', $container);

        $self = new Collector(array($sample));

        $collection = $self->make(new Container);

        $expect = $container;

        $actual = $collection->getContainer();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_debugger_sample_valid()
    {
        $debugger = new ErrorHandler('development');

        $sample = new SampleComponent('debugger', $debugger);

        $self = new Collector(array($sample));

        $collection = $self->make(new Container);

        $actual = $collection->has(System::DEBUGGER);

        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatcher_sample_valid()
    {
        $dispatcher = new Dispatcher(new Router);

        $sample = new SampleComponent('dispatcher', $dispatcher);

        $self = new Collector(array($sample));

        $collection = $self->make(new Container);

        $actual = $collection->has(System::DISPATCHER);

        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_http_sample_valid()
    {
        $server = array('REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/', 'SERVER_NAME' => 'localhost', 'SERVER_PORT' => '8000');

        $request = new ServerRequest($server);

        $response = new Response;

        $values = array($request, $response);

        $sample = new SampleComponent('http', $values);

        $self = new Collector(array($sample));

        $collection = $self->make(new Container);

        $actual = $collection->has(System::REQUEST);

        $this->assertTrue($actual);

        $actual = $collection->has(System::RESPONSE);

        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_middleware_collected()
    {
        $item = 'Rougin\Slytherin\Fixture\Components\MiddlewareComponent';

        // Define a middleware component to collect ---
        $self = new Collector(array($item));

        $container = new Container;
        // --------------------------------------------

        // Make the collection from the container ---
        $collection = $self->make($container);
        // ------------------------------------------

        // Verify the middleware component was registered ------
        $this->assertTrue($collection->has(System::MIDDLEWARE));
        // -----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_middleware_sample_valid()
    {
        $middleware = new Middleware;

        $sample = new SampleComponent('middleware', $middleware);

        $self = new Collector(array($sample));

        $collection = $self->make(new Container);

        $actual = $collection->has(System::MIDDLEWARE);

        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    /**
     * @return void
     */
    public function test_failed_if_container_invalid_in_component()
    {
        $this->doExpectException(self::CONTAINER_NOT_FOUND);

        $item = new InvalidContainerComponent;

        $self = new Collector(array($item));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_http_not_array_in_component()
    {
        $this->doExpectException(self::COMPONENT_NOT_ARRAY);

        $item = new InvalidHttpNotArray;

        $self = new Collector(array($item));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_http_request_invalid_in_component()
    {
        $this->doExpectException(self::REQUEST_NOT_FOUND);

        $item = new InvalidHttpRequest;

        $self = new Collector(array($item));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_http_response_invalid_in_component()
    {
        $this->doExpectException(self::RESPONSE_NOT_FOUND);

        $item = new InvalidHttpResponse;

        $self = new Collector(array($item));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_middleware_invalid_in_component()
    {
        $this->doExpectException(self::MIDDLEWARE_NOT_FOUND);

        $item = new InvalidMiddlewareComponent;

        $self = new Collector(array($item));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_template_invalid_in_component()
    {
        $this->doExpectException(self::TEMPLATE_NOT_FOUND);

        $item = new InvalidTemplateComponent;

        $self = new Collector(array($item));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_passed_if_template_sample_valid()
    {
        $renderer = new Renderer(array());

        $sample = new SampleComponent('template', $renderer);

        $self = new Collector(array($sample));

        $collection = $self->make(new Container);

        $actual = $collection->has(System::TEMPLATE);

        $this->assertTrue($actual);
    }
}

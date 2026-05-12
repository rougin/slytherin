<?php

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Components\SampleComponent;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CollectorTest extends Testcase
{
    /**
     * @return void
     */
    public function test_failed_if_component_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doSetExpectedException($expect);

        $sample = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        new Collector(array($sample));
    }

    /**
     * @return void
     */
    public function test_failed_if_container_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doSetExpectedException($expect);

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

        $this->doSetExpectedException($expect);

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

        $this->doSetExpectedException($expect);

        $sample = new SampleComponent('dispatcher', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
    }

    /**
     * @return void
     */
    public function test_failed_if_http_request_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doSetExpectedException($expect);

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

        $this->doSetExpectedException($expect);

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

        $this->doSetExpectedException($expect);

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

        $this->doSetExpectedException($expect);

        $sample = new SampleComponent('template', new NewClass);

        $self = new Collector(array($sample));

        $self->make(new Container);
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
}

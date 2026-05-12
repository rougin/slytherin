<?php

namespace Rougin\Slytherin\System;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SystemTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_debugger_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doSetExpectedException($expect);

        $this->container->set(System::DEBUGGER, new NewClass);

        $system = new System($this->container);

        $system->run();
    }

    /**
     * @return void
     */
    public function test_failed_if_dispatcher_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doSetExpectedException($expect);

        $this->container->set(System::DISPATCHER, new NewClass);
        $system = new System($this->container);

        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $system->handle($request);
    }

    /**
     * @return void
     */
    public function test_failed_if_integration_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';
        $this->doSetExpectedException($expect);

        $system = new System($this->container);

        $sample = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $system->integrate(array($sample));
    }

    /**
     * @return void
     */
    public function test_failed_if_request_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doSetExpectedException($expect);

        $this->container->set(System::REQUEST, new NewClass);

        $system = new System($this->container);

        $system->run();
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
    }
}

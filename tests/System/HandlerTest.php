<?php

namespace Rougin\Slytherin\System;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Routing\Route;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class HandlerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_response_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';
        $this->doSetExpectedException($expect);

        $this->container->set(System::RESPONSE, new NewClass);

        $fn = function ()
        {
            return 'test';
        };

        $route = new Route('GET', '/', $fn);

        $handler = new Handler($route, $this->container);

        $server = array('REQUEST_URI' => '/');

        $server['REQUEST_METHOD'] = 'GET';

        $server['SERVER_NAME'] = 'localhost';

        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $handler->handle($request);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
    }
}

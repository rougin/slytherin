<?php

namespace Rougin\Slytherin\Sample;

use Rougin\Slytherin\Middleware\Interop;
use Rougin\Slytherin\Sample\Handlers\Cors;
use Rougin\Slytherin\Sample\Handlers\Parsed\Request;
use Rougin\Slytherin\Sample\Handlers\Parsed\Response;
use Rougin\Slytherin\Sample\Packages\MiddlewarePackage;
use Rougin\Slytherin\Sample\Packages\SamplePackage;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SampleTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Sample\Builder
     */
    protected $builder;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->builder = new Builder;

        $this->builder->setCookies($_COOKIE);

        $this->builder->setFiles($_FILES);

        $this->builder->setQuery((array) $_GET);

        $this->builder->setParsed($_POST);

        $this->builder->setServer($_SERVER);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_sample_package_added()
    {
        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('GET', '/');

        $this->expectOutputString('Welcome home!');

        $this->builder->make()->run();

        $timezone = date_default_timezone_get();

        $expected = 'Asia/Manila';

        $this->assertEquals($expected, $timezone);
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_sample_text()
    {
        $this->builder->setUrl('GET', '/hello');

        $this->expectOutputString('Hello world!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_arguments_in_uri()
    {
        $this->builder->setUrl('GET', '/hi/Rougin');

        $this->expectOutputString('Hello, Rougin!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_sest_depot_as_the_constructor()
    {
        $this->builder->setUrl('GET', '/');

        $this->expectOutputString('Welcome home!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_sest_depot_as_the_argument()
    {
        $this->builder->setUrl('GET', '/param');

        $this->expectOutputString('Welcome param!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_only_string_as_the_output()
    {
        $this->builder->setUrl('GET', '/string');

        $this->expectOutputString('This is a simple string.');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_no_slash_in_the_route()
    {
        $this->builder->setUrl('GET', '/without-slash');

        $this->expectOutputString('This is a simple string.');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_callable_as_the_route_and_string_only_as_the_output()
    {
        $this->builder->setUrl('GET', '/callable');

        $this->expectOutputString('Welcome call!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_callable_as_the_route_with_params_and_string_only_as_the_output()
    {
        $this->builder->setUrl('GET', '/call/Slytherin');

        $this->expectOutputString('Welcome Slytherin!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_callable_as_the_route_with_multiple_params()
    {
        $this->builder->setUrl('GET', '/call/Slytherin/18');

        $this->expectOutputString('Welcome Slytherin, 18!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_middleware_changing_the_request_constructor()
    {
        $this->builder->addHandler(new Cors);

        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->addHandler(new Request);

        $this->builder->setUrl('GET', '/handler/conts');

        $this->expectOutputString('Hello, Slytherin!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_middleware_changing_the_request_parameter()
    {
        $this->builder->addHandler(new Cors);

        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->addHandler(new Request);

        $this->builder->setUrl('GET', '/handler/param');

        $this->expectOutputString('Hello, Slytherin!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_middleware_changing_the_response_parameter()
    {
        $this->builder->addHandler(new Cors);

        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->addHandler(new Response);

        $this->builder->setUrl('GET', '/response');

        $this->expectOutputString('From middleware!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_callable_middleware_changing_the_response_parameter()
    {
        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->setUrl('GET', '/middleware');

        $this->expectOutputString('From callable middleware!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_interop_middleware_changing_the_response_parameter()
    {
        $this->builder->addPackage(new MiddlewarePackage);

        // @codeCoverageIgnoreStart
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->builder->setUrl('GET', '/interop');

        $this->expectOutputString('From interop!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_without_forward_slash_in_the_router_namespace()
    {
        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('GET', '/world');

        $this->expectOutputString('Hello string world!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_uploaded_file_from_request()
    {
        /** @var string */
        $file = realpath(__DIR__ . '/EMDAER.txt');

        $this->builder->addFile('files', $file);

        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('POST', '/upload');

        $this->expectOutputString('The file is EMDAER.txt!');

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_with_route_to_json()
    {
        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('GET', '/encoded');

        $this->expectOutputString('"Encoded world!"');

        $this->builder->make()->run();
    }
}

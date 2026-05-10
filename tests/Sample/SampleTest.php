<?php

namespace Rougin\Slytherin\Sample;

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
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_callable_middleware_responded()
    {
        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->setUrl('GET', '/middleware');

        $expect = 'From callable middleware!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_callable_route_responded()
    {
        $this->builder->setUrl('GET', '/callable');

        $expect = 'Welcome call!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_callable_route_with_multiple_params()
    {
        $this->builder->setUrl('GET', '/call/Slytherin/18');

        $expect = 'Welcome Slytherin, 18!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_callable_route_with_params()
    {
        $this->builder->setUrl('GET', '/call/Slytherin');

        $expect = 'Welcome Slytherin!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_depot_as_argument_responded()
    {
        $this->builder->setUrl('GET', '/param');

        $expect = 'Welcome param!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_depot_as_constructor_responded()
    {
        $this->builder->setUrl('GET', '/');

        $expect = 'Welcome home!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_interop_middleware_responded()
    {
        $this->builder->addPackage(new MiddlewarePackage);

        $this->checkIfInteropExists();

        $this->builder->setUrl('GET', '/interop');

        $expect = 'From interop!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_middleware_constructor_changed()
    {
        $this->builder->addHandler(new Cors);

        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->addHandler(new Request);

        $this->builder->setUrl('GET', '/handler/conts');

        $expect = 'Hello, Slytherin!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_middleware_parameter_changed()
    {
        $this->builder->addHandler(new Cors);

        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->addHandler(new Request);

        $this->builder->setUrl('GET', '/handler/param');

        $expect = 'Hello, Slytherin!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_middleware_response_changed()
    {
        $this->builder->addHandler(new Cors);

        $this->builder->addPackage(new MiddlewarePackage);

        $this->builder->addHandler(new Response);

        $this->builder->setUrl('GET', '/response');

        $expect = 'From middleware!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_no_slash_route_responded()
    {
        $this->builder->setUrl('GET', '/without-slash');

        $expect = 'This is a simple string.';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_package_added()
    {
        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('GET', '/');

        $expect = 'Welcome home!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();

        // Verify the package set the timezone ---
        $timezone = date_default_timezone_get();

        $expect = 'Asia/Manila';

        $this->assertEquals($expect, $timezone);
        // ----------------------------------------
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_route_to_json_responded()
    {
        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('GET', '/encoded');

        $expect = '"Encoded world!"';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_sample_text_responded()
    {
        $this->builder->setUrl('GET', '/hello');

        $expect = 'Hello world!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_string_output_responded()
    {
        $this->builder->setUrl('GET', '/string');

        $expect = 'This is a simple string.';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_uploaded_file_responded()
    {
        // Add a sample file to the builder ---
        $file = __DIR__ . '/EMDAER.txt';

        $this->builder->addFile('files', $file);
        // ------------------------------------

        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('POST', '/upload');

        $expect = 'The file is EMDAER.txt!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_uri_arguments_responded()
    {
        $this->builder->setUrl('GET', '/hi/Rougin');

        $expect = 'Hello, Rougin!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @runInSeparateProcess
     *
     * @return void
     */
    public function test_passed_if_without_forward_slash_namespace()
    {
        $this->builder->addPackage(new SamplePackage);

        $this->builder->setUrl('GET', '/world');

        $expect = 'Hello string world!';
        $this->expectOutputString($expect);

        $this->builder->make()->run();
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->builder = new Builder;

        /** @var array<string, string> */
        $cookies = $_COOKIE;
        $this->builder->setCookies($cookies);

        /** @var array<string, array<string, mixed[]>> */
        $files = $_FILES;
        $this->builder->setFiles($files);

        /** @var array<string, string> */
        $get = $_GET;
        $this->builder->setQuery($get);

        /** @var array<string, string> */
        $post = $_POST;
        $this->builder->setParsed($post);

        /** @var array<string, string> */
        $server = $_SERVER;
        $this->builder->setServer($server);
    }
}

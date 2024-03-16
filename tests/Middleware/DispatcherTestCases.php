<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Middleware\Interop;
use Rougin\Slytherin\Middleware\Wrapper;
use Rougin\Slytherin\System\Lastone;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Middleware\Dispatcher|\Rougin\Slytherin\Middleware\StratigilityDispatcher
     */
    protected $dispatcher;

    /**
     * @return void
     */
    public function test_processing_middlewares_as_double_pass()
    {
        $fn = function ($request, $response, $next)
        {
            $response = $next($request, $response)->withStatus(404);

            return $response->withHeader('X-Slytherin', time());
        };

        $this->dispatcher->push($fn);

        $expected = (int) 404;

        $actual = $this->process()->getStatusCode();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_processing_middlewares_as_single_pass()
    {
        $class = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        if (is_a($this->dispatcher, $class))
        {
            /** @var \Rougin\Slytherin\Middleware\StratigilityDispatcher */
            $zend = $this->dispatcher;

            // @codeCoverageIgnoreStart
            if (! $zend->hasPsr() && ! $zend->hasFactory())
            {
                $this->markTestSkipped('Zend Stratigility does not support single pass callbacks.');
            }
            // @codeCoverageIgnoreEnd
        }

        $time = (int) time();

        $fn = function ($request, $next) use ($time)
        {
            $response = $next($request);

            return $response->withHeader('X-Slytherin', $time);
        };

        $this->dispatcher->push($fn);

        $expected = array($time);

        $actual = $this->process()->getHeader('X-Slytherin');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_processing_middlewares_as_interop_middleware()
    {
        $class = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        if (is_a($this->dispatcher, $class))
        {
            /** @var \Rougin\Slytherin\Middleware\StratigilityDispatcher */
            $zend = $this->dispatcher;

            // @codeCoverageIgnoreStart
            if (! $zend->hasPsr() && ! $zend->hasFactory())
            {
                $this->markTestSkipped('Zend Stratigility does not support single pass callbacks.');
            }
            // @codeCoverageIgnoreEnd
        }

        $fn = function ($request, $next)
        {
            $response = $next($request);

            return $response->withHeader('Content-Type', 'application/json');
        };

        $this->dispatcher->push($fn);

        $expected = array('application/json');

        $actual = $this->process()->getHeader('Content-Type');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_processing_middlewares_as_string()
    {
        // @codeCoverageIgnoreStart
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not installed.');
        }
        // @codeCoverageIgnoreEnd

        $interop = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        $this->dispatcher->push($interop);

        $expected = 500;

        $actual = $this->process()->getStatusCode();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_adding_middlewars_as_interop_middleware()
    {
        // @codeCoverageIgnoreStart
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not installed.');
        }
        // @codeCoverageIgnoreEnd

        $interop = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        $expected = array(new Wrapper($interop));

        $this->dispatcher->push(array($interop));

        $actual = $this->dispatcher->stack();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_middlewares()
    {
        // @codeCoverageIgnoreStart
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->dispatcher->push('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');

        $actual = $this->dispatcher->stack();

        $this->assertCount(1, $actual);
    }

    /**
     * Processes the defined middleware dispatcher and return its response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function process()
    {
        $server = array('REQUEST_METHOD' => 'GET');

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'localhost';

        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        return $this->dispatcher->process($request, new Lastone);
    }
}

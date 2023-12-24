<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Middleware\Interop;
use Rougin\Slytherin\Middleware\Wrapper;
use Rougin\Slytherin\System\Lastone;
use Rougin\Slytherin\Testcase;

/**
 * Dispatcher Test Cases
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Middleware\Dispatch
     */
    protected $dispatcher;

    /**
     * Tests DispatcherInterface::process with a double pass callback.
     *
     * @return void
     */
    public function testProcessMethodWithDoublePassCallback()
    {
        $fn = function ($request, $response, $next)
        {
            $response = $next($request, $response)->withStatus(404);

            return $response->withHeader('X-Slytherin', time());
        };

        $this->dispatcher->push($fn);

        $expected = (integer) 404;

        $result = $this->process()->getStatusCode();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::process with a single pass callback.
     *
     * @return void
     */
    public function testProcessMethodWithSinglePassCallback()
    {
        $class = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        if (is_a($this->dispatcher, $class))
        {
            /** @var \Rougin\Slytherin\Middleware\StratigilityDispatcher */
            $zend = $this->dispatcher;

            if (! $zend->hasPsr() && ! $zend->hasFactory())
            {
                $this->markTestSkipped('Current Stratigility version does not support single pass callbacks');
            }
        }

        $time = (integer) time();

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
     * Tests DispatcherInterface::process with DelegateInterface callback.
     *
     * @return void
     */
    public function testProcessMethodWithDelagateInterfaceCallback()
    {
        $class = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        if (is_a($this->dispatcher, $class))
        {
            /** @var \Rougin\Slytherin\Middleware\StratigilityDispatcher */
            $zend = $this->dispatcher;

            if (! $zend->hasPsr() && ! $zend->hasFactory())
            {
                $this->markTestSkipped('Current Stratigility version does not support single pass callbacks');
            }
        }

        $fn = function ($request, $next)
        {
            $response = $next($request);

            return $response->withHeader('Content-Type', 'application/json');
        };

        $this->dispatcher->push($fn);

        $expected = array('application/json');

        $result = $this->process()->getHeader('Content-Type');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::process with string.
     *
     * @return void
     */
    public function testProcessMethodWithString()
    {
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not yet installed');
        }

        $interop = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        $this->dispatcher->push($interop);

        $expected = 500;

        $result = $this->process()->getStatusCode();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::push with array.
     *
     * @return void
     */
    public function testPushMethodWithArray()
    {
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not yet installed');
        }

        $interop = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        $expected = array(new Wrapper($interop));

        $this->dispatcher->push(array($interop));

        $result = $this->dispatcher->stack();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests DispatcherInterface::stack.
     *
     * @return void
     */
    public function testStackMethod()
    {
        if (! Interop::exists())
        {
            $this->markTestSkipped('Interop middleware/s not yet installed');
        }

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

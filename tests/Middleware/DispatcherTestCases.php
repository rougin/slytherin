<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Server\Wrapper;
use Rougin\Slytherin\System\Endofline;
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
     * @var \Rougin\Slytherin\Server\Dispatch
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
        $stratigility = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        $wrapper = 'Zend\Stratigility\Middleware\CallableMiddlewareWrapper';

        if (is_a($this->dispatcher, $stratigility) && ! class_exists($wrapper))
        {
            $message = 'Stratigility\'s current installed version does not accept single pass middlewares';

            $this->markTestSkipped((string) $message);
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
        $stratigility = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        $wrapper = 'Zend\Stratigility\Middleware\CallableMiddlewareWrapper';

        if (is_a($this->dispatcher, $stratigility) && ! class_exists($wrapper))
        {
            $message = 'Stratigility\'s current version does not accept delegates';

            $this->markTestSkipped((string) $message);
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
        $stratigility = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        $wrapper = 'Zend\Stratigility\Middleware\CallableMiddlewareWrapper';

        if (is_a($this->dispatcher, $stratigility) && ! class_exists($wrapper))
        {
            $message = 'Stratigility\'s current version does not accept PSR-15 middlewares';

            $this->markTestSkipped((string) $message);
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

        return $this->dispatcher->process($request, new Endofline);
    }
}

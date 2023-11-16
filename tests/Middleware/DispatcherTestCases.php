<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Http\ServerRequest;

/**
 * Dispatcher Test Cases
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends \LegacyPHPUnit\TestCase
{
    /**
     * @var \Rougin\Slytherin\Middleware\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * Tests DispatcherInterface::process with a double pass callback.
     *
     * @return void
     */
    public function testProcessMethodWithDoublePassCallback()
    {
        $this->dispatcher->push(function ($request, $response, $next) {
            $response = $next($request, $response)->withStatus(404);

            return $response->withHeader('X-Slytherin', time());
        });

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

        if (is_a($this->dispatcher, $stratigility) && ! class_exists($wrapper)) {
            $message = 'Stratigility\'s current installed version';

            $message .= ' does not accept single pass middlewares';

            $this->markTestSkipped((string) $message);
        }

        $time = (integer) time();

        $this->dispatcher->push(function ($request, $next) use ($time) {
            $response = $next($request);

            return $response->withHeader('X-Slytherin', $time);
        });

        $expected = array((integer) $time);

        $result = $this->process()->getHeader('X-Slytherin');

        $this->assertEquals($expected, $result);
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

        if (is_a($this->dispatcher, $stratigility) && ! class_exists($wrapper)) {
            $message = 'Stratigility\'s current version';

            $message .= (string) ' does not accept delegates';

            $this->markTestSkipped((string) $message);
        }

        $this->dispatcher->push(function ($request, $delegate) {
            $response = $delegate->process($request);

            return $response->withHeader('Content-Type', 'application/json');
        });

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

        if (is_a($this->dispatcher, $stratigility) && ! class_exists($wrapper)) {
            $message = 'Stratigility\'s current version';

            $message .= ' does not accept PSR-15 middlewares';

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
        $expected = array('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');

        $expected[] = 'Rougin\Slytherin\Middleware\FinalResponse';

        $this->dispatcher->push($expected);

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

        $this->dispatcher->push('Rougin\Slytherin\Middleware\FinalResponse');

        $expected = (integer) 2;

        $result = $this->dispatcher->stack();

        $this->assertCount($expected, $result);
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

        return $this->dispatcher->process($request, new Delegate);
    }
}

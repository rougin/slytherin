<?php

namespace Rougin\Slytherin\Middleware;

/**
 * Middleware Test Cases
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MiddlewareTestCases extends \PHPUnit_Framework_TestCase
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
        $callback = function ($request, $response, $next) {
            $response = $next($request, $response)->withStatus(404);

            return $response->withHeader('X-Slytherin', time());
        };

        $this->dispatcher->push($callback);

        $this->assertEquals(404, $this->response()->getStatusCode());
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
            $message = 'Stratigility\'s current version does not accept single pass middlewares.';

            $this->markTestSkipped($message);
        }

        $time = time();

        $callback = function ($request, $next) use ($time) {
            $response = $next($request);

            return $response->withHeader('X-Slytherin', $time);
        };

        $this->dispatcher->push($callback);

        $this->assertEquals(array($time), $this->response()->getHeader('X-Slytherin'));
    }

    /**
     * Tests DispatcherInterface::process with \Interop\Http\ServerMiddleware\DelegateInterface.
     *
     * @return void
     */
    public function testProcessMethodWithDelagateInterface()
    {
        $stratigility = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        $wrapper = 'Zend\Stratigility\Middleware\CallableMiddlewareWrapper';

        if (is_a($this->dispatcher, $stratigility) && ! class_exists($wrapper)) {
            $message = 'Stratigility\'s current version does not accept delegates.';

            $this->markTestSkipped($message);
        }

        $callback = function ($request, $delegate) {
            $response = $delegate->process($request);

            return $response->withHeader('Content-Type', 'application/json');
        };

        $this->dispatcher->push($callback);

        $this->assertEquals(array('application/json'), $this->response()->getHeader('Content-Type'));
    }

    /**
     * Tests DispatcherInterface::push with array.
     *
     * @return void
     */
    public function testPushMethodWithArray()
    {
        $stack = array();

        array_push($stack, 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');
        array_push($stack, 'Rougin\Slytherin\Fixture\Middlewares\FinalResponse');

        $this->dispatcher->push($stack);

        $this->assertCount(2, $this->dispatcher->stack());
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

        $this->assertCount(2, $this->dispatcher->stack());
    }

    /**
     * Processes the defined middleware dispatcher and return its response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function response()
    {
        $server = array();

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new \Rougin\Slytherin\Http\ServerRequest($server);

        return $this->dispatcher->process($request, new Delegate);
    }
}

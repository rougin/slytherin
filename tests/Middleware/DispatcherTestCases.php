<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Middleware\Multiple\Interop05;
use Rougin\Slytherin\Middleware\Multiple\Slytherin;
use Rougin\Slytherin\System\Lastone;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Middleware\Dispatcher
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_double_pass_processed()
    {
        // Add a double-pass middleware to the stack ---------------
        $fn = function ($request, $response, $next)
        {
            $response = $next($request, $response)->withStatus(404);

            return $response->withHeader('X-Slytherin', time());
        };

        $this->self->push($fn);
        // ---------------------------------------------------------

        // Verify the middleware modified the status code ---
        $expect = 404;

        $actual = $this->process()->getStatusCode();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_interop_middleware_added()
    {
        $this->checkIfInteropExists();

        $interop = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        // Add an interop middleware wrapped as an array ---
        $expect = array(new Wrapper($interop));

        $this->self->push(array($interop));
        // -------------------------------------------------

        // Verify the middleware was wrapped correctly ---
        $actual = $this->self->stack();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_interop_middleware_processed()
    {
        $class = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        if (is_a($this->self, $class))
        {
            /** @var \Rougin\Slytherin\Middleware\StratigilityDispatcher */
            $zend = $this->self;

            // @codeCoverageIgnoreStart
            if (! $zend->hasPsr() && ! $zend->hasFactory())
            {
                $text = 'Zend Stratigility does not support single pass callbacks.';

                $this->markTestSkipped($text);
            }
            // @codeCoverageIgnoreEnd
        }

        // Add a single-pass middleware that sets a content type header -----
        $fn = function ($request, $next)
        {
            $response = $next($request);

            return $response->withHeader('Content-Type', 'application/json');
        };

        $this->self->push($fn);
        // ------------------------------------------------------------------

        // Verify the middleware set the Content-Type header ---
        $expect = array('application/json');

        $actual = $this->process()->getHeader('Content-Type');

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_middleware_string_processed()
    {
        $this->checkIfInteropExists();

        $interop = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        $this->self->push($interop);

        // Verify the middleware returned a 500 status ---
        $expect = 500;

        $actual = $this->process()->getStatusCode();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_middlewares_pushed_as_array()
    {
        // Create two single-pass middlewares --------------
        $fn1 = function ($request, $next)
        {
            $response = $next($request);

            return $response->withHeader('X-First', 'one');
        };

        $fn2 = function ($request, $next)
        {
            $response = $next($request);

            return $response->withHeader('X-Second', 'two');
        };
        // -------------------------------------------------

        $this->self->push(array($fn1, $fn2));

        // Verify both headers were set ---------------
        $headers = $this->process()->getHeaders();

        $this->assertArrayHasKey('X-First', $headers);

        $this->assertArrayHasKey('X-Second', $headers);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_middlewares_retrieved()
    {
        $this->checkIfInteropExists();

        $class = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        $this->self->push($class);

        // Verify one middleware was added to the stack ---
        $actual = $this->self->stack();

        $this->assertCount(1, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_mixed_middleware_interfaces()
    {
        $this->checkIfInteropExists();

        // Push both Slytherin and Interop middleware ---
        $expect = array('Rougin Gutib');

        $items = array(new Slytherin, new Interop05);

        $this->self->push($items);
        // ----------------------------------------------

        // Verify the middleware set the name header ---
        $actual = $this->process()->getHeader('name');

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_non_callable_wrapped()
    {
        // Push a non-callable, non-middleware object ---
        $object = new \stdClass;

        $this->self->push($object);
        // ----------------------------------------------

        // Verify the object was wrapped in a Wrapper ---
        $expect = 'Rougin\Slytherin\Middleware\Wrapper';

        $actual = $this->self->stack();

        $this->assertInstanceOf($expect, $actual[0]);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_single_pass_processed()
    {
        $class = 'Rougin\Slytherin\Middleware\StratigilityDispatcher';

        if (is_a($this->self, $class))
        {
            /** @var \Rougin\Slytherin\Middleware\StratigilityDispatcher */
            $zend = $this->self;

            // @codeCoverageIgnoreStart
            if (! $zend->hasPsr() && ! $zend->hasFactory())
            {
                $text = 'Zend Stratigility does not support single pass callbacks.';

                $this->markTestSkipped($text);
            }
            // @codeCoverageIgnoreEnd
        }

        // Add a single-pass middleware that sets a timestamp header ---
        $time = time();

        $fn = function ($request, $next) use ($time)
        {
            $response = $next($request);

            return $response->withHeader('X-Slytherin', $time);
        };

        $this->self->push($fn);
        // ------------------------------------------------------------

        // Verify the middleware set the X-Slytherin header ---
        $expect = array($time);

        $actual = $this->process()->getHeader('X-Slytherin');

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stack_method_retrieved()
    {
        // Push a simple pass-through middleware ---
        $fn = function ($request, $next)
        {
            return $next($request);
        };

        $this->self->push($fn);
        // -----------------------------------------

        // Verify one middleware was added to the stack ---
        $actual = $this->self->stack();

        $this->assertCount(1, $actual);
        // ------------------------------------------------
    }

    /**
     * Processes the defined middleware dispatcher and return its response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function process()
    {
        $server = array('REQUEST_URI' => '/');

        $server['REQUEST_METHOD'] = 'GET';

        $server['SERVER_NAME'] = 'localhost';

        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $last = new Lastone;

        return $this->self->process($request, $last);
    }
}

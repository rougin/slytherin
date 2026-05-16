<?php

namespace Rougin\Slytherin\Dispatching;

use Rougin\Slytherin\Testcase;

/**
 * @deprecated since ~0.9, use "Routing\DispatcherTestCases" instead.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class DispatcherTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Routing\DispatcherInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_http_method_invalid()
    {
        $expect = 'BadMethodCallException';

        $this->doExpectException($expect);

        $this->self->dispatch('TEST', '/test-method');
    }

    /**
     * @return void
     */
    public function test_failed_if_route_not_found()
    {
        $expect = 'BadMethodCallException';

        $this->doExpectException($expect);

        $this->self->dispatch('GET', '/test');
    }

    /**
     * @return void
     */
    public function test_passed_if_dispatcher_exists()
    {
        $expect = 'Rougin\Slytherin\Routing\DispatcherInterface';

        $this->assertInstanceOf($expect, $this->self);
    }

    /**
     * @return void
     */
    public function test_passed_if_route_dispatched_as_callback()
    {
        $route = $this->self->dispatch('GET', '/hi');

        // Verify the callback result is correct ----
        /** @var callable */
        $callback = $route->getHandler();

        $params = $route->getParams();

        $actual = call_user_func($callback, $params);

        $this->assertEquals('Hi', $actual);
        // ------------------------------------------
    }
}

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
     * Tests DispatcherInterface::dispatch with a callback.
     *
     * @return void
     */
    public function testProcessMethodWithCallback()
    {
        $this->dispatcher->push(function ($request, $next) {
            $response = $next($request)->withStatus(404);

            return $response->withHeader('X-Slytherin', time());
        });

        list($request) = $this->http();

        $response = $this->dispatcher->process($request, new Delegate);

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Returns ServerRequestInterface and ResponseInterface objects.
     *
     * @return array
     */
    protected function http()
    {
        $server = array();

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new \Rougin\Slytherin\Http\ServerRequest($server);

        return array($request, new \Rougin\Slytherin\Http\Response);
    }
}

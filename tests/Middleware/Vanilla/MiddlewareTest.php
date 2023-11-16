<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

/**
 * Stratigility Middleware Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends \Rougin\Slytherin\Testcase
{
    /**
     * Tests __invoke() method
     *
     * @return void
     */
    public function testInvokeMethod()
    {
        if (! interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $this->markTestSkipped('Interop Middleware is not installed.');
        }

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI']    = '/';
        $_SERVER['SERVER_NAME']    = 'localhost';
        $_SERVER['SERVER_PORT']    = '8000';

        $middleware = new \Rougin\Slytherin\Middleware\Vanilla\Middleware;
        $request    = new \Rougin\Slytherin\Http\ServerRequest($_SERVER);
        $response   = new \Rougin\Slytherin\Http\Response;

        $middleware->push(function ($request, $next = null) {
            return $next($request);
        });

        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');

        $response = $middleware($request, $response, $middleware->getStack());

        $this->assertEquals('First! Second! Last!', (string) $response->getBody());
    }

    /**
     * Tests process() method
     *
     * @return void
     */
    public function testProcessMethod()
    {
        if (! interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $this->markTestSkipped('Interop Middleware is not installed.');
        }

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI']    = '/';
        $_SERVER['SERVER_NAME']    = 'localhost';
        $_SERVER['SERVER_PORT']    = '8000';

        $middleware = new \Rougin\Slytherin\Middleware\Vanilla\Middleware;
        $request = new \Rougin\Slytherin\Http\ServerRequest($_SERVER);
        $response = new \Rougin\Slytherin\Http\Response;

        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');
        $middleware->push('Rougin\Slytherin\Middleware\FinalResponse');

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $middleware($request, $response));
    }
}

<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

/**
 * Stratigility Middleware Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests __invoke() method
     *
     * @return void
     */
    public function testInvokeMethod()
    {
        $middleware = new \Rougin\Slytherin\Middleware\Vanilla\Middleware;
        $stream     = new \Rougin\Slytherin\Http\Stream;
        $uri        = new \Rougin\Slytherin\Http\Uri;
        $request    = new \Rougin\Slytherin\Http\ServerRequest('1.1', array(), $stream, '/', 'GET', $uri);
        $response   = new \Rougin\Slytherin\Http\Response;

        $middleware->push(function ($request, $next = null) {
            return $next($request);
        });

        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');

        $response = $middleware($request, $response, $middleware->getQueue());

        $this->assertEquals('First! Second! Last!', (string) $response->getBody());
    }

    /**
     * Tests process() method
     *
     * @return void
     */
    public function testProcessMethod()
    {
        $middleware = new \Rougin\Slytherin\Middleware\Vanilla\Middleware;
        $stream     = new \Rougin\Slytherin\Http\Stream;
        $uri        = new \Rougin\Slytherin\Http\Uri;
        $request    = new \Rougin\Slytherin\Http\ServerRequest('1.1', array(), $stream, '/', 'GET', $uri);

        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');
        $middleware->push('Rougin\Slytherin\Middleware\FinalResponse');

        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $middleware->dispatch($request));
    }
}

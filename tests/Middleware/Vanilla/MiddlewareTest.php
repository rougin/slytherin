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
        $queue = array();

        array_push($queue, 'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware');
        array_push($queue, 'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware');
        array_push($queue, 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');

        $middleware = new \Rougin\Slytherin\Middleware\Vanilla\Middleware;

        $stream   = new \Rougin\Slytherin\Http\Stream;
        $uri      = new \Rougin\Slytherin\Http\Uri;
        $request  = new \Rougin\Slytherin\Http\ServerRequest('1.1', array(), $stream, '/', 'GET', $uri);
        $response = new \Rougin\Slytherin\Http\Response;

        $response = $middleware($request, $response, $queue);

        $this->assertEquals('First! Second! Last!', (string) $response->getBody());
    }
}

<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

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
        if (! class_exists('Zend\Stratigility\MiddlewarePipe')) {
            $this->markTestSkipped('Zend Stratigility is not installed.');
        }

        $stack = array();

        array_push($stack, 'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware');
        array_push($stack, 'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware');
        array_push($stack, 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');

        $pipeline   = new \Zend\Stratigility\MiddlewarePipe;
        $middleware = new \Rougin\Slytherin\Middleware\Stratigility\Middleware($pipeline);

        $stream   = new \Rougin\Slytherin\Http\Stream;
        $uri      = new \Rougin\Slytherin\Http\Uri;
        $request  = new \Rougin\Slytherin\Http\ServerRequest('1.1', array(), $stream, '/', 'GET', $uri);
        $response = new \Rougin\Slytherin\Http\Response;

        $response = $middleware($request, $response, $stack);

        $this->assertEquals('First! Second! Last!', (string) $response->getBody());
    }
}

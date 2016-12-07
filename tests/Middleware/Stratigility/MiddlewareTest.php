<?php

namespace Rougin\Slytherin\Middleware\Auryn;

use Rougin\Slytherin\Http\Uri;
use Rougin\Slytherin\Http\Stream;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Middleware\Stratigility\Middleware;

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

        $queue = [
            'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware',
            'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware',
            'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware',
        ];

        $middleware = new Middleware(new \Zend\Stratigility\MiddlewarePipe);
        $request    = new ServerRequest('1.1', [], new Stream, '/', 'GET', new Uri);
        $response   = new Response;

        $response = $middleware($request, $response, $queue);

        $this->assertEquals('First! Second! Last!', (string) $response->getBody());
    }
}

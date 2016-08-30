<?php

namespace Rougin\Slytherin\Test\Middleware\Auryn;

use Zend\Stratigility\MiddlewarePipe;

use Rougin\Slytherin\Middleware\Stratigility\Middleware;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\Http\Uri;
use Rougin\Slytherin\Test\Fixture\Http\Response;
use Rougin\Slytherin\Test\Fixture\Http\ServerRequest;

/**
 * Stratigility Middleware Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends PHPUnit_Framework_TestCase
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
            'Rougin\Slytherin\Test\Fixture\Middlewares\FirstMiddleware',
            'Rougin\Slytherin\Test\Fixture\Middlewares\SecondMiddleware',
            'Rougin\Slytherin\Test\Fixture\Middlewares\LastMiddleware',
        ];

        $middleware = new Middleware(new MiddlewarePipe);
        $request    = new ServerRequest('1.1', [], null, '/', 'GET', new Uri);
        $response   = new Response;

        $response = $middleware($request, $response, $queue);

        $this->assertEquals('First! Second! Last!', $response->getBody());
    }
}

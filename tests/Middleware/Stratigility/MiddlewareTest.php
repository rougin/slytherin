<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

/**
 * Stratigility Middleware Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI']    = '/';
        $_SERVER['SERVER_NAME']    = 'localhost';
        $_SERVER['SERVER_PORT']    = '8000';

        $stack = array();

        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $pipeline = new \Zend\Stratigility\MiddlewarePipe;
        $middleware = new \Rougin\Slytherin\Middleware\Stratigility\Middleware($pipeline);

        $request = new \Rougin\Slytherin\Http\ServerRequest($_SERVER);
        $response = new \Rougin\Slytherin\Http\Response;

        $response = $middleware($request, $response, $stack);

        $this->assertEquals('First! Second! Last!', (string) $response->getBody());
    }
}

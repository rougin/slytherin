<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Middleware\Stratigility\Middleware;
use Rougin\Slytherin\System\Lastone;
use Rougin\Slytherin\Testcase;
use Zend\Stratigility\MiddlewarePipe;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends Testcase
{
    /**
     * @return void
     */
    public function testInvokeMethod()
    {
        if (! class_exists('Zend\Stratigility\MiddlewarePipe'))
        {
            $this->markTestSkipped('Zend Stratigility is not installed.');
        }

        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $stack = array();

        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $pipeline = new MiddlewarePipe;
        $middleware = new Middleware($pipeline, $stack);

        $request = new ServerRequest($server);
        $response = new Response;

        $expected = 'First! Second! Last!';

        $response = $middleware->process($request, new Lastone);
        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }
}

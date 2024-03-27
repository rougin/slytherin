<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\System\Lastone;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends Testcase
{
    /**
     * @return void
     */
    public function test_processing_multiple_middlewares()
    {
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $middleware = new Middleware;
        $request = new ServerRequest($server);
        $response = new Response;

        $fn = function ($request, $next)
        {
            return $next($request);
        };

        $middleware->push($fn);

        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');

        $expected = 'First! Second! Last!';

        $response = $middleware->process($request, new Lastone);
        $actual = (string) $response->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_processing_one_middleware()
    {
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $middleware = new Middleware;
        $request = new ServerRequest($server);

        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');

        $expected = 'Psr\Http\Message\ResponseInterface';

        $actual = $middleware->process($request, new Lastone);

        $this->assertInstanceOf($expected, $actual);
    }
}

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
    public function test_passed_if_multiple_middlewares_processed()
    {
        // Prepare the server request -----------
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $middleware = new Middleware;
        $request = new ServerRequest($server);
        $response = new Response;
        // ---------------------------------------

        // Push multiple middleware classes into the stack ---
        $fn = function ($request, $next)
        {
            return $next($request);
        };

        $middleware->push($fn);

        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware');
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\LastMiddleware');
        // ----------------------------------------------------

        // Verify the combined middleware output is correct ---
        $expect = 'First! Second! Last!';

        $response = $middleware->process($request, new Lastone);
        $actual = $response->getBody();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_single_middleware_processed()
    {
        $this->checkIfInteropExists();

        // Prepare the server request -----------
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $middleware = new Middleware;
        $request = new ServerRequest($server);
        // ---------------------------------------

        // Push an interop middleware class ---
        $middleware->push('Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware');
        // -----------------------------------

        // Verify the response is a PSR-7 ResponseInterface ----
        $expect = 'Psr\Http\Message\ResponseInterface';

        $actual = $middleware->process($request, new Lastone);

        $this->assertInstanceOf($expect, $actual);
        // -----------------------------------------------------
    }
}

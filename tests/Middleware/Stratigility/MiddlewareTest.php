<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\System\Lastone;
use Rougin\Slytherin\Testcase;
use Zend\Stratigility\MiddlewarePipe;

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
        // @codeCoverageIgnoreStart
        $this->checkIfStratigilityExists();
        // @codeCoverageIgnoreEnd

        // Prepare the server request -----------
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';
        // ---------------------------------------

        // Set up the Stratigility pipeline with middleware ---
        $stack = array();

        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $pipeline = new MiddlewarePipe;
        $middleware = new Middleware($pipeline, $stack);

        $request = new ServerRequest($server);
        $response = new Response;
        // ---------------------------------------------------

        // Verify the combined middleware output is correct ---
        $expect = 'First! Second! Last!';

        $response = $middleware->process($request, new Lastone);
        $actual = $response->getBody();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------
    }
}

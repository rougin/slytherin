<?php

namespace Rougin\Slytherin\Middleware\Vanilla;

use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Middleware\MiddlewareTestCases;
use Rougin\Slytherin\System\Lastone;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends MiddlewareTestCases
{
    /**
     * @return void
     */
    public function test_passed_if_single_middleware_processed()
    {
        $this->checkIfInteropExists();

        // Prepare the server request --------
        $server = array('REQUEST_URI' => '/');
        $server['REQUEST_METHOD'] = 'GET';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);
        // -----------------------------------

        $middleware = 'Rougin\Slytherin\Fixture\Middlewares\InteropMiddleware';

        // Push an interop middleware class ---
        $this->self->push($middleware);
        // ------------------------------------

        // Verify if it is a PSR-7 ResponseInterface ---
        $expect = 'Psr\Http\Message\ResponseInterface';

        $last = new Lastone;

        $actual = $this->self->process($request, $last);

        $this->assertInstanceOf($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Middleware;
    }
}

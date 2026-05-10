<?php

namespace Rougin\Slytherin\Middleware;

use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\System\Lastone;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class CallbackTest extends Testcase
{
    /**
     * @return void
     */
    public function test_failed_if_middleware_not_callable()
    {
        $expect = 'Exception';

        $this->doSetExpectedException($expect);

        // Prepare a server request ---------------
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);
        // ----------------------------------------

        // Create a callback with a non-callable class ---
        $response = new Response;

        $handler = new Lastone;

        $callback = new Callback('stdClass', $response);
        // -----------------------------------------------

        // Attempt to process the non-callable middleware ---
        $callback->process($request, $handler);
        // -------------------------------------------------
    }
}

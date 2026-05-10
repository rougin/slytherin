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
    public function test_processing_non_callable_middleware_throws_exception()
    {
        $this->doSetExpectedException('Exception');

        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);

        $response = new Response;

        $handler = new Lastone;

        $callback = new Callback('stdClass', $response);

        $callback->process($request, $handler);
    }
}

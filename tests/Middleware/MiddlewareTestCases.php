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
class MiddlewareTestCases extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Middleware\DispatcherInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_multiple_middlewares_processed()
    {
        // Prepare the server request --------
        $server = array('REQUEST_URI' => '/');

        $server['REQUEST_METHOD'] = 'GET';

        $server['SERVER_NAME'] = 'localhost';

        $server['SERVER_PORT'] = '8000';

        $request = new ServerRequest($server);
        // -----------------------------------

        $response = new Response;

        // Push multiple middleware classes into the stack -------------
        $fn = function ($request, $next)
        {
            /** @var callable $next */
            $next = $next;

            /** @var \Psr\Http\Message\ResponseInterface */
            return $next($request);
        };

        $this->self->push($fn);

        $first = 'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware';
        $this->self->push($first);

        $next = 'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware';
        $this->self->push($next);

        $last = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';
        $this->self->push($last);
        // -------------------------------------------------------------

        // Verify the combined middleware output is correct ---
        $expect = 'First! Second! Last!';

        $final = new Lastone;

        $response = $this->self->process($request, $final);

        $actual = $response->getBody();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------
    }
}

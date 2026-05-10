<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * @return void
     */
    public function test_failed_if_http_method_empty()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set an empty HTTP method ---
        $this->request->withMethod('');
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_host_header_preserved()
    {
        $expect = array('localhost');

        // Set a custom Host header first ---------
        $request = $this->request->withHeader('Host', $expect);
        // ----------------------------------------

        // Update the URI while preserving the host ----------
        $uri = new Uri('https://www.google.com');

        $request = $request->withUri($uri, true);
        // ---------------------------------------------------

        // Verify the original host header was kept ---
        $actual = $request->getHeader('Host');

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_http_method_set()
    {
        $expect = 'POST';

        // Set the HTTP method on the request ------
        $request = $this->request->withMethod($expect);
        // -----------------------------------------

        // Verify the method is returned correctly ---
        $actual = $request->getMethod();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_request_target_retrieved()
    {
        $expect = '/lorem-ipsum';

        // Set the request target ---------------
        $request = $this->request->withRequestTarget($expect);
        // --------------------------------------

        // Verify the target is returned correctly ---
        $actual = $request->getRequestTarget();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_uri_retrieved()
    {
        $expect = new Uri('https://www.google.com');

        // Set the URI on the request ------
        $request = $this->request->withUri($expect);
        // ---------------------------------

        // Verify the URI is returned correctly ---
        $actual = $request->getUri();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->request = new Request;
    }
}

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
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_http_method_empty()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        $this->self->withMethod('');
    }

    /**
     * @return void
     */
    public function test_passed_if_host_header_preserved()
    {
        $expect = array('localhost');

        $self = $this->self->withHeader('Host', $expect);

        // Update the URI while preserving the host ---
        $uri = new Uri('https://www.google.com');

        $self = $self->withUri($uri, true);
        // --------------------------------------------

        // Verify the original host header was kept ---
        $actual = $self->getHeader('Host');

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_http_method_set()
    {
        $expect = 'POST';

        // Set the HTTP method on the request ---
        $self = $this->self->withMethod($expect);
        // --------------------------------------

        // Verify the method is returned correctly ---
        $actual = $self->getMethod();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_request_target_retrieved()
    {
        $expect = '/lorem-ipsum';

        // Set the request target ----------------------
        $self = $this->self->withRequestTarget($expect);
        // ---------------------------------------------

        // Verify the target is returned correctly ---
        $actual = $self->getRequestTarget();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_uri_retrieved()
    {
        $expect = new Uri('https://www.google.com');

        // Set the URI on the request --------
        $self = $this->self->withUri($expect);
        // -----------------------------------

        // Verify the URI is returned correctly ---
        $actual = $self->getUri();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Request;
    }
}

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
     * @var boolean
     */
    protected $isV2 = false;

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

        $this->doExpectException($expect);

        $this->self->withMethod('');
    }

    /**
     * @return void
     */
    public function test_failed_if_method_is_not_a_string()
    {
        $expect = $this->isV2 ? 'TypeError' : 'InvalidArgumentException';

        $this->doExpectException($expect);

        $this->self->withMethod(array());
    }

    /**
     * @return void
     */
    public function test_passed_if_custom_method_is_accepted()
    {
        $expect = 'CUSTOM';

        $self = $this->self->withMethod($expect);

        $actual = $self->getMethod();

        $this->assertEquals($expect, $actual);
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
    public function test_passed_if_method_preserves_case()
    {
        $expect = 'head';

        $self = $this->self->withMethod($expect);

        $actual = $self->getMethod();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_request_target_found()
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
    public function test_passed_if_uri_found()
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
    public function test_passed_if_uri_sets_host_as_header()
    {
        $uri = new Uri('https://www.foo.com/bar');

        $self = $this->self->withUri($uri);

        $actual = $self->getHeaderLine('host');

        $this->assertEquals('www.foo.com', $actual);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->isV2 = Interop::isVersion2();

        $this->self = new Request;
    }
}

<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UriTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\UriInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_host_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a non-string as the host ---
        $this->self->withHost(array());
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_path_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a non-string as the path ---
        $this->self->withPath(array());
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_port_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a port out of range ---
        $this->self->withPort(70000);
        // --------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_query_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a non-string as the query ---
        $this->self->withQuery(array());
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_scheme_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set an invalid scheme ---
        $this->self->withScheme('123invalid');
        // ------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_authority_retrieved()
    {
        $expect = 'me@roug.in:400';

        // Verify the authority string is returned ---
        $actual = $this->self->getAuthority();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_fragment_updated()
    {
        // Update the fragment on the URI --------
        $expect = 'test';

        $self = $this->self->withFragment('test');
        // ---------------------------------------

        // Verify the fragment is returned correctly ---
        $actual = $self->getFragment();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_host_updated()
    {
        // Update the host on the URI --------------
        $expect = 'google.com';

        $self = $this->self->withHost('google.com');
        // -----------------------------------------

        // Verify the host is returned correctly ---
        $actual = $self->getHost();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_path_updated()
    {
        // Update the path on the URI ---------
        $expect = '/test';

        $self = $this->self->withPath('/test');
        // ------------------------------------

        // Verify the path is returned correctly ---
        $actual = $self->getPath();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_port_set()
    {
        // Set the port on the URI --------
        $expect = 500;

        $self = $this->self->withPort(500);
        // --------------------------------

        // Verify the port is returned correctly ---
        $actual = $self->getPort();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_query_updated()
    {
        // Update the query string on the URI ------
        $expect = 'type=user';

        $self = $this->self->withQuery('type=user');
        // -----------------------------------------

        // Verify the query is returned correctly ---
        $actual = $self->getQuery();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_scheme_retrieved()
    {
        // Update the scheme on the URI --------
        $expect = 'http';

        $self = $this->self->withScheme('http');
        // -------------------------------------

        // Verify the scheme is returned correctly ---
        $actual = $self->getScheme();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_uri_converted_to_string()
    {
        $expect = 'https://me@roug.in:400/about';

        // Verify the URI converts to string correctly ---
        $actual = $this->self->__toString();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_user_info_set()
    {
        // Set the user info on the URI ---
        $expect = 'username:password';
        // --------------------------------

        $self = $this->self->withUserInfo('username', 'password');

        // Verify the user info is returned correctly ---
        $actual = $self->getUserInfo();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Uri('https://me@roug.in:400/about');
    }
}

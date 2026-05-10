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
    protected $uri;

    /**
     * @return void
     */
    public function test_failed_if_host_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a non-string as the host ---
        $this->uri->withHost(array());
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
        $this->uri->withPath(array());
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
        $this->uri->withPort(70000);
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
        $this->uri->withQuery(array());
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
        $this->uri->withScheme('123invalid');
        // ------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_authority_retrieved()
    {
        $expect = 'me@roug.in:400';

        // Verify the authority string is returned correctly ---
        $actual = $this->uri->getAuthority();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_fragment_updated()
    {
        $expect = 'test';

        // Update the fragment on the URI ---------
        $uri = $this->uri->withFragment('test');
        // ----------------------------------------

        // Verify the fragment is returned correctly ---
        $actual = $uri->getFragment();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_host_updated()
    {
        $expect = 'google.com';

        // Update the host on the URI ----------
        $uri = $this->uri->withHost('google.com');
        // -------------------------------------

        // Verify the host is returned correctly ---
        $actual = $uri->getHost();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_path_updated()
    {
        $expect = '/test';

        // Update the path on the URI ------
        $uri = $this->uri->withPath('/test');
        // ---------------------------------

        // Verify the path is returned correctly ---
        $actual = $uri->getPath();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_port_set()
    {
        $expect = 500;

        // Set the port on the URI ----
        $uri = $this->uri->withPort(500);
        // ----------------------------

        // Verify the port is returned correctly ---
        $actual = $uri->getPort();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_query_updated()
    {
        $expect = 'type=user';

        // Update the query string on the URI -------
        $uri = $this->uri->withQuery('type=user');
        // ------------------------------------------

        // Verify the query is returned correctly ---
        $actual = $uri->getQuery();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_scheme_retrieved()
    {
        $expect = 'http';

        // Update the scheme on the URI ----
        $uri = $this->uri->withScheme('http');
        // ----------------------------------

        // Verify the scheme is returned correctly ---
        $actual = $uri->getScheme();

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
        $actual = $this->uri->__toString();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_user_info_set()
    {
        $expect = 'username:password';

        // Set the user info on the URI ------------------
        $uri = $this->uri->withUserInfo('username', 'password');
        // -----------------------------------------------------

        // Verify the user info is returned correctly ---
        $actual = $uri->getUserInfo();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->uri = new Uri('https://me@roug.in:400/about');
    }
}

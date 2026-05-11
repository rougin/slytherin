<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ServerRequestTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_parsed_body_is_not_array_or_object()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        $this->self->withParsedBody(4711);
    }

    /**
     * @return void
     */
    public function test_failed_if_parsed_body_type_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a string as the parsed body ---
        $this->self->withParsedBody('string');
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_uploaded_file_type_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Prepare invalid uploaded file data ---
        $file = array('not_a_uploaded_file');

        $files = array('file' => $file);
        // --------------------------------------

        // Attempt to set non-UploadedFileInterface items ---
        $this->self->withUploadedFiles($files);
        // --------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_attribute_fallback_used()
    {
        // Retrieve a non-existent attribute with a default value ---
        $expect = 'fallback';

        $actual = $this->self->getAttribute('nonexistent', $expect);
        // ----------------------------------------------------------

        // Verify the default value is returned ---
        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_attribute_removed()
    {
        $expect = array('user' => 'John Doe');

        // Set multiple request attributes --------------------
        $self = $this->self->withAttribute('user', 'John Doe');

        $self = $self->withAttribute('age', 20);

        $self = $self->withoutAttribute('age');
        // ----------------------------------------------------

        // Verify the attribute was excluded ---
        $actual = $self->getAttributes();

        $this->assertEquals($expect, $actual);
        // -------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_cookie_params_default_to_superglobal()
    {
        $expect = $_COOKIE;

        $actual = $this->self->getCookieParams();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_cookie_params_retrieved()
    {
        // Set the cookie parameters ------------------------
        $expect = array('name' => 'John Doe', 'age' => '19');

        $self = $this->self->withCookieParams($expect);
        // --------------------------------------------------

        // Verify the cookies are returned correctly ---
        $actual = $self->getCookieParams();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_missing_attribute_returns_null()
    {
        $actual = $this->self->getAttribute('not_found');

        $this->assertNull($actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_parsed_body_retrieved()
    {
        // Set the parsed body on the request -----------
        $expect = array('page' => 10, 'name' => 'users');

        $self = $this->self->withParsedBody($expect);
        // ----------------------------------------------

        // Verify the body is returned correctly ---
        $actual = $self->getParsedBody();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_query_params_retrieved()
    {
        // Set the query string parameters --------------
        $expect = array('page' => 10, 'name' => 'users');

        $self = $this->self->withQueryParams($expect);
        // ----------------------------------------------

        // Verify the query params are returned correctly ---
        $actual = $self->getQueryParams();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_request_attributes_retrieved()
    {
        // Set a request attribute ----------------------------
        $expect = array('user' => 'John Doe');

        $self = $this->self->withAttribute('user', 'John Doe');
        // ----------------------------------------------------

        // Verify the attributes are returned correctly ---
        $actual = $self->getAttributes();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_server_params_retrieved()
    {
        $expect = $_SERVER;

        // Verify the parameters are returned correctly ---
        $actual = $this->self->getServerParams();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_single_uploaded_file_parsed()
    {
        // Prepare a single uploaded file structure ---
        $server = array('REQUEST_URI' => '/');
        $server['REQUEST_METHOD'] = 'GET';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $uploaded = array('file' => array());

        $error = 0;
        $file = '/tmp/test.txt';
        $name = 'test.txt';
        $size = 617369;
        $type = 'application/pdf';

        $uploaded['file']['error'] = $error;
        $uploaded['file']['name'] = $name;
        $uploaded['file']['size'] = $size;
        $uploaded['file']['tmp_name'] = $file;
        $uploaded['file']['type'] = $type;
        // --------------------------------------------

        $self = new ServerRequest($server, array(), array(), $uploaded);

        // Verify the file is parsed into an UploadedFile instance ------
        $uploaded = new UploadedFile($file, $size, $error, $name, $type);

        $expect = array('file' => array($uploaded));

        $actual = $self->getUploadedFiles();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_uploaded_files_retrieved()
    {
        // Create an "UploadedFile" instance ---
        $error = 0;
        $name = 'test.txt';
        $size = 617369;
        $file = '/tmp/test.txt';
        $type = 'application/pdf';
        // -------------------------------------

        $uploaded = new UploadedFile($file, $size, $error, $name, $type);

        $expect = array('file' => array($uploaded));

        // Set the uploaded files on the request -------
        $self = $this->self->withUploadedFiles($expect);
        // ---------------------------------------------

        // Verify the files are returned correctly ---
        $actual = $self->getUploadedFiles();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        /** @var array<string, string> */
        $server = $_SERVER;

        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI'] = '/';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $_SERVER = $server;

        $files = array('file' => array());

        $files['file']['error'] = array('0');
        $files['file']['name'] = array('test.txt');
        $files['file']['size'] = array('617369');
        $files['file']['tmp_name'] = array('/tmp/test.txt');
        $files['file']['type'] = array('application/pdf');

        $this->self = new ServerRequest($server, array(), array(), $files);
    }
}

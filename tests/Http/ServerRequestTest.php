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
    protected $request;

    /**
     * @return void
     */
    public function test_failed_if_parsed_body_type_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a string as the parsed body ---
        $this->request->withParsedBody('string');
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
        $files = array('file' => array('not_a_uploaded_file'));
        // --------------------------------------

        // Attempt to set non-UploadedFileInterface items ---
        $this->request->withUploadedFiles($files);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_attribute_fallback_used()
    {
        $expect = 'fallback';

        // Retrieve a non-existent attribute with a default value ---
        $actual = $this->request->getAttribute('nonexistent', $expect);
        // ---------------------------------------------------------

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

        // Set multiple request attributes ----------
        $request = $this->request->withAttribute('user', 'John Doe');

        $request = $request->withAttribute('age', 20);

        $request = $request->withoutAttribute('age');
        // ------------------------------------------

        // Verify the attribute was excluded ---
        $actual = $request->getAttributes();

        $this->assertEquals($expect, $actual);
        // ------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_cookie_params_retrieved()
    {
        $expect = array('name' => 'John Doe', 'age' => '19');

        // Set the cookie parameters ---------------
        $request = $this->request->withCookieParams($expect);
        // -----------------------------------------

        // Verify the cookies are returned correctly ---
        $actual = $request->getCookieParams();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_parsed_body_retrieved()
    {
        $expect = array('page' => 10, 'name' => 'users');

        // Set the parsed body on the request -----------
        $request = $this->request->withParsedBody($expect);
        // ----------------------------------------------

        // Verify the body is returned correctly ---
        $actual = $request->getParsedBody();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_query_params_retrieved()
    {
        $expect = array('page' => 10, 'name' => 'users');

        // Set the query string parameters ----------
        $request = $this->request->withQueryParams($expect);
        // ------------------------------------------

        // Verify the query params are returned correctly ---
        $actual = $request->getQueryParams();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_request_attributes_retrieved()
    {
        $expect = array('user' => 'John Doe');

        // Set a request attribute ---------------
        $request = $this->request->withAttribute('user', 'John Doe');
        // ---------------------------------------

        // Verify the attributes are returned correctly ---
        $actual = $request->getAttributes();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_server_params_retrieved()
    {
        $expect = $_SERVER;

        // Verify the server parameters are returned correctly ---
        $actual = $this->request->getServerParams();

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_single_uploaded_file_parsed()
    {
        // Prepare a single uploaded file structure ------------
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
        // -----------------------------------------------------

        // Create the server request with the uploaded file ----
        $request = new ServerRequest($server, array(), array(), $uploaded);
        // -----------------------------------------------------

        // Verify the file is parsed into an UploadedFile instance ---
        $uploaded = new UploadedFile($file, $size, $error, $name, $type);

        $expect = array('file' => array($uploaded));

        $actual = $request->getUploadedFiles();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_uploaded_files_retrieved()
    {
        // Create an UploadedFile instance ---------------
        $error = 0;
        $name = 'test.txt';
        $size = 617369;
        $file = '/tmp/test.txt';
        $type = 'application/pdf';

        $uploaded = new UploadedFile($file, $size, $error, $name, $type);
        // -----------------------------------------------

        $expect = array('file' => array($uploaded));

        // Set the uploaded files on the request ---------
        $request = $this->request->withUploadedFiles($expect);
        // ------------------------------------------------

        // Verify the files are returned correctly ---
        $actual = $request->getUploadedFiles();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
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

        $this->request = new ServerRequest($server, array(), array(), $files);
    }
}

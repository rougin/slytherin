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

    /**
     * @return void
     */
    public function test_getting_request_attributes()
    {
        $expected = array('user' => 'John Doe');

        $request = $this->request->withAttribute('user', 'John Doe');

        $actual = $request->getAttributes();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_cookie_params()
    {
        $expected = array('name' => 'John Doe', 'age' => '19');

        $request = $this->request->withCookieParams($expected);

        $actual = $request->getCookieParams();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_parsed_body()
    {
        $expected = array('page' => 10, 'name' => 'users');

        $request = $this->request->withParsedBody($expected);

        $actual = $request->getParsedBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_query_params()
    {
        $expected = array('page' => 10, 'name' => 'users');

        $request = $this->request->withQueryParams($expected);

        $actual = $request->getQueryParams();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_server_params()
    {
        $expected = $_SERVER;

        $actual = $this->request->getServerParams();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_uploaded_files()
    {
        $error = 0;
        $name = 'test.txt';
        $size = 617369;
        $file = '/tmp/test.txt';
        $type = 'application/pdf';

        $uploaded = new UploadedFile($file, $size, $error, $name, $type);

        $expected = array('file' => array($uploaded));

        $request = $this->request->withUploadedFiles($expected);

        $actual = $request->getUploadedFiles();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_uploaded_files_with_a_single_uploaded_file()
    {
        $server = array('REQUEST_URI' => '/');
        $server['REQUEST_METHOD'] = 'GET';
        $server['SERVER_NAME'] = 'localhost';
        $server['SERVER_PORT'] = '8000';

        $uploaded = array('file' => array());

        // Define the sample file ---
        $error = 0;
        $file = '/tmp/test.txt';
        $name = 'test.txt';
        $size = 617369;
        $type = 'application/pdf';
        // --------------------------

        $uploaded['file']['error'] = $error;
        $uploaded['file']['name'] = $name;
        $uploaded['file']['size'] = $size;
        $uploaded['file']['tmp_name'] = $file;
        $uploaded['file']['type'] = $type;

        $request = new ServerRequest($server, array(), array(), $uploaded);

        $uploaded = new UploadedFile($file, $size, $error, $name, $type);

        $expected = array('file' => array($uploaded));

        $actual = $request->getUploadedFiles();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_attribute_with_default_value()
    {
        $expected = 'fallback';

        $actual = $this->request->getAttribute('nonexistent', $expected);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_excluding_a_request_attribute()
    {
        $expected = array('user' => 'John Doe');

        $request = $this->request->withAttribute('user', 'John Doe');

        $request = $request->withAttribute('age', 20);

        $request = $request->withoutAttribute('age');

        $actual = $request->getAttributes();

        $this->assertEquals($expected, $actual);
    }
}

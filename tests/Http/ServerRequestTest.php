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
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '8000';

        $files = array('file' => array());

        $files['file']['error'] = array('0');
        $files['file']['name'] = array('test.txt');
        $files['file']['size'] = array('617369');
        $files['file']['tmp_name'] = array('/tmp/test.txt');
        $files['file']['type'] = array('application/pdf');

        $this->request = new ServerRequest($_SERVER, array(), array(), $files);
    }

    /**
     * @return void
     */
    public function test_getting_attribute_from_server_params()
    {
        $expected = 'localhost';

        $actual = $this->request->getAttribute('SERVER_NAME');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_request_attributes()
    {
        // TODO: To be removed in v1.0.0. $_SERVER must not be included in attributes. ---
        $expected = array_merge($_SERVER, array('user' => 'John Doe'));
        // -------------------------------------------------------------------------------

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
        $expected = (array) $_SERVER;

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
    public function test_excluding_a_request_attribute()
    {
        // TODO: To be removed in v1.0.0. $_SERVER must not be included in attributes.
        $expected = array_merge($_SERVER, array('user' => 'John Doe'));

        $request = $this->request->withAttribute('user', 'John Doe');

        $request = $request->withAttribute('age', 20);

        $request = $request->withoutAttribute('age');

        $actual = $request->getAttributes();

        $this->assertEquals($expected, $actual);
    }
}

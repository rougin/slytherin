<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * Server Request Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ServerRequestTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * Sets up the request instance.
     *
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
     * Tests ServerRequestInterface::getAttribute if it has $_SERVER values.
     *
     * @return void
     */
    public function testGetAttributeMethodWithServerParams()
    {
        $expected = (string) 'localhost';

        $actual = $this->request->getAttribute('SERVER_NAME');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ServerRequestInterface::getAttributes.
     *
     * @return void
     */
    public function testGetAttributesMethod()
    {
        // TODO: To be removed in v1.0.0. $_SERVER must not be included in attributes. ---
        $expected = array_merge($_SERVER, array('user' => 'John Doe'));
        // -------------------------------------------------------------------------------

        $request = $this->request->withAttribute('user', 'John Doe');

        $actual = $request->getAttributes();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ServerRequestInterface::getCookieParams.
     *
     * @return void
     */
    public function testGetCookieParamsMethod()
    {
        $expected = array('name' => 'John Doe', 'age' => '19');

        $request = $this->request->withCookieParams($expected);

        $actual = $request->getCookieParams();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ServerRequestInterface::getParsedBody.
     *
     * @return void
     */
    public function testGetParsedBodyMethod()
    {
        $expected = array('page' => 10, 'name' => 'users');

        $request = $this->request->withParsedBody($expected);

        $actual = $request->getParsedBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ServerRequestInterface::getQueryParams.
     *
     * @return void
     */
    public function testGetQueryParamsMethod()
    {
        $expected = array('page' => 10, 'name' => 'users');

        $request = $this->request->withQueryParams($expected);

        $actual = $request->getQueryParams();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ServerRequestInterface::getServerParams.
     *
     * @return void
     */
    public function testGetServerParamsMethod()
    {
        $expected = (array) $_SERVER;

        $actual = $this->request->getServerParams();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ServerRequestInterface::getUploadedFiles.
     *
     * @return void
     */
    public function testGetUploadedFilesMethod()
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
     * Tests ServerRequestInterface::getUploadedFiles with a single uploaded file.
     *
     * @return void
     */
    public function testGetUploadedFilesMethodWithSingleUploadedFile()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '8000';

        $uploaded = array('file' => array());

        $uploaded['file']['error'] = '0';
        $uploaded['file']['name'] = 'test.txt';
        $uploaded['file']['size'] = '617369';
        $uploaded['file']['tmp_name'] = '/tmp/test.txt';
        $uploaded['file']['type'] = 'application/pdf';

        $request = new ServerRequest($_SERVER, array(), array(), $uploaded);

        $error = (int) $uploaded['file']['error'];
        $file = $uploaded['file']['tmp_name'];
        $name = $uploaded['file']['name'];
        $size = (int) $uploaded['file']['size'];
        $type = $uploaded['file']['type'];

        $uploaded = new UploadedFile($file, $size, $error, $name, $type);

        $expected = array('file' => array($uploaded));

        $actual = $request->getUploadedFiles();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ServerRequestInterface::withoutAttribute.
     *
     * @return void
     */
    public function testWithoutAttributeMethod()
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

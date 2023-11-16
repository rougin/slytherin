<?php

namespace Rougin\Slytherin\Http;

/**
 * Server Request Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ServerRequestTest extends \Rougin\Slytherin\Testcase
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

        $uploaded = array('file' => array());

        $uploaded['file']['error'] = array(0);
        $uploaded['file']['name'] = array('test.txt');
        $uploaded['file']['size'] = array(617369);
        $uploaded['file']['tmp_name'] = array('/tmp/test.txt');
        $uploaded['file']['type'] = array('application/pdf');

        $this->request = new ServerRequest($_SERVER, array(), array(), $uploaded);
    }

    /**
     * Tests ServerRequestInterface::getAttribute if it has $_SERVER values.
     *
     * @return void
     */
    public function testGetAttributeMethodWithServerParams()
    {
        $expected = (string) 'localhost';

        $result = $this->request->getAttribute('SERVER_NAME');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests ServerRequestInterface::getAttributes.
     *
     * @return void
     */
    public function testGetAttributesMethod()
    {
        // TODO: To be removed in v1.0.0. $_SERVER must not be included in attributes.
        $expected = array_merge($_SERVER, array('user' => 'John Doe'));

        $request = $this->request->withAttribute('user', 'John Doe');

        $result = $request->getAttributes();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests ServerRequestInterface::getCookieParams.
     *
     * @return void
     */
    public function testGetCookieParamsMethod()
    {
        $expected = array('name' => 'John Doe', 'age' => 19);

        $request = $this->request->withCookieParams($expected);

        $result = $request->getCookieParams();

        $this->assertEquals($expected, $result);
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

        $result = $request->getParsedBody();

        $this->assertEquals($expected, $result);
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

        $result = $request->getQueryParams();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests ServerRequestInterface::getServerParams.
     *
     * @return void
     */
    public function testGetServerParamsMethod()
    {
        $expected = (array) $_SERVER;

        $result = $this->request->getServerParams();

        $this->assertEquals($expected, $result);
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

        $result = (array) $this->request->getUploadedFiles();

        $this->assertEquals($expected, $result);
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

        $uploaded['file']['error'] = 0;
        $uploaded['file']['name'] = 'test.txt';
        $uploaded['file']['size'] = 617369;
        $uploaded['file']['tmp_name'] = '/tmp/test.txt';
        $uploaded['file']['type'] = 'application/pdf';

        $request = new ServerRequest($_SERVER, array(), array(), $uploaded);

        $error = $uploaded['file']['error'];
        $name = $uploaded['file']['name'];
        $size = $uploaded['file']['size'];
        $file = $uploaded['file']['tmp_name'];
        $type = $uploaded['file']['type'];

        $uploaded = new UploadedFile($file, $size, $error, $name, $type);

        $expected = array('file' => array($uploaded));

        $result = (array) $request->getUploadedFiles();

        $this->assertEquals($expected, $result);
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

        $result = $request->getAttributes();

        $this->assertEquals($expected, $result);
    }
}

<?php

namespace Rougin\Slytherin\Http;

/**
 * Server Request Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ServerRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * Sets up the request.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Psr\Http\Message\ServerRequestInterface')) {
            $this->markTestSkipped('PSR-7 is not installed.');
        }

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
     * Tests getRequestTarget() and withRequestTarget().
     *
     * @return void
     */
    public function testRequestTarget()
    {
        $expected = '/lorem-ipsum';
        $request  = $this->request->withRequestTarget($expected);

        $this->assertEquals($expected, $request->getRequestTarget());
    }

    /**
     * Tests getCookieParams() and withCookieParams().
     *
     * @return void
     */
    public function testCookieParams()
    {
        $expected = array('name' => 'John Doe', 'age' => 19);
        $request  = $this->request->withCookieParams($expected);

        $this->assertEquals($expected, $request->getCookieParams());
    }

    /**
     * Tests getQueryParams() and withQueryParams().
     *
     * @return void
     */
    public function testQueryParams()
    {
        $expected = array('page' => 10, 'name' => 'users');
        $request  = $this->request->withQueryParams($expected);

        $this->assertEquals($expected, $request->getQueryParams());
    }

    /**
     * Tests getParsedBody().
     *
     * @return void
     */
    public function testParsedBody()
    {
        $this->assertEmpty($this->request->getParsedBody());
    }

    /**
     * Tests getServerParams().
     *
     * @return void
     */
    public function testServerParams()
    {
        $this->assertEquals($_SERVER, $this->request->getServerParams());
    }

    /**
     * Tests getUploadedFiles() and withUploadedFiles().
     *
     * @return void
     */
    public function testUploadedFiles()
    {
        $files = array('file' => array());

        $error = $files['file']['error'] = 0;
        $name = $files['file']['name'] = 'test.txt';
        $size = $files['file']['size'] = 617369;
        $file = $files['file']['tmp_name'] = '/tmp/test.txt';
        $type = $files['file']['type'] = 'application/pdf';

        $expected = array('file' => array(new UploadedFile($file, $size, $error, $name, $type)));

        $this->assertEquals($expected, $this->request->getUploadedFiles());

        $request = $this->request->withUploadedFiles($expected);

        $this->assertEquals($expected, $request->getUploadedFiles());
    }

    /**
     * Tests getAttribute(), getAttributes(), withAttribute() and withoutAttribute().
     *
     * @return void
     */
    public function testAttribute()
    {
        $attributes = array('user' => 'John Doe', 'age' => 20);

        $request = $this->request;

        foreach ($attributes as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $this->assertEquals($attributes['user'], $request->getAttribute('user'));

        $request = $request->withoutAttribute('age');

        $newAttributes = $request->getAttributes();

        $this->assertFalse(isset($newAttributes['age']));
    }
}

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
        $_SERVER['REQUEST_URI']    = '/';
        $_SERVER['SERVER_NAME']    = 'localhost';
        $_SERVER['SERVER_PORT']    = '8000';

        $this->request = new \Rougin\Slytherin\Http\ServerRequest($_SERVER);
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
        $expected = array(new \Rougin\Slytherin\Http\UploadedFile('/tmp/test.txt'));
        $request  = $this->request->withUploadedFiles($expected);

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
        $request    = $this->request;

        foreach ($attributes as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        $arrayEqual = $attributes == $request->getAttributes();
        $itemEqual  = $request->getAttribute('user') == 'John Doe';

        $request = $request->withoutAttribute('age');

        $newAttributes = $request->getAttributes();

        $keyExists = isset($newAttributes['age']);

        $this->assertTrue($itemEqual && $arrayEqual && ! $keyExists);
    }
}

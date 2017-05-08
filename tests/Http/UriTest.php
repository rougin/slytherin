<?php

namespace Rougin\Slytherin\Http;

/**
 * Uri Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class UriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;

    /**
     * Sets up the uri.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Psr\Http\Message\UriInterface')) {
            $this->markTestSkipped('PSR-7 is not installed.');
        }

        $this->uri = new \Rougin\Slytherin\Http\Uri('https://me@rougin.github.io:400/about');
    }

    /**
     * Tests getScheme() and withScheme().
     *
     * @return void
     */
    public function testScheme()
    {
        $uri = $this->uri->withScheme('http');

        $this->assertEquals('http', $uri->getScheme());
    }

    /**
     * Tests getAuthority().
     *
     * @return void
     */
    public function testGetAuthority()
    {
        $this->assertEquals('me@rougin.github.io:400', $this->uri->getAuthority());
    }

    /**
     * Tests getUserInfo() and withUserInfo().
     *
     * @return void
     */
    public function testUserInfo()
    {
        $uri = $this->uri->withUserInfo('username', 'password');

        $this->assertEquals('username[:password]', $uri->getUserInfo());
    }

    /**
     * Tests getHost() and withHost().
     *
     * @return void
     */
    public function testHost()
    {
        $uri = $this->uri->withHost('google.com');

        $this->assertEquals('google.com', $uri->getHost());
    }

    /**
     * Tests getPort() and withPort().
     *
     * @return void
     */
    public function testPort()
    {
        $uri = $this->uri->withPort(500);

        $this->assertEquals(500, $uri->getPort());
    }

    /**
     * Tests getQuery() and withQuery().
     *
     * @return void
     */
    public function testQuery()
    {
        $uri = $this->uri->withQuery('type=user');

        $this->assertEquals('type=user', $uri->getQuery());
    }

    /**
     * Tests getFragment() and withFragment().
     *
     * @return void
     */
    public function testFragment()
    {
        $uri = $this->uri->withFragment('test');

        $this->assertEquals('test', $uri->getFragment());
    }

    /**
     * Tests getPath() and withPath().
     *
     * @return void
     */
    public function testPath()
    {
        $uri = $this->uri->withPath('/test');

        $this->assertEquals('/test', $uri->getPath());
    }

    /**
     * Tests __toString().
     *
     * @return void
     */
    public function testToString()
    {
        $this->assertEquals('https://me@rougin.github.io:400/about', (string) $this->uri);
    }
}

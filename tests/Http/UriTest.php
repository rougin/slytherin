<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * URI Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class UriTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;

    /**
     * Sets up the URI instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->uri = new Uri('https://me@roug.in:400/about');
    }

    /**
     * Tests UriInterface::getScheme.
     *
     * @return void
     */
    public function testGetSchemeMethod()
    {
        $expected = (string) 'http';

        $uri = $this->uri->withScheme('http');

        $actual = $uri->getScheme();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::getAuthority.
     *
     * @return void
     */
    public function testGetAuthorityMethod()
    {
        $expected = 'me@roug.in:400';

        $actual = $this->uri->getAuthority();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::getUserInfo.
     *
     * @return void
     */
    public function testGetUserInfoMethod()
    {
        $expected = (string) 'username:password';

        $uri = $this->uri->withUserInfo('username', 'password');

        $actual = $uri->getUserInfo();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::getHost.
     *
     * @return void
     */
    public function testGetHostMethod()
    {
        $expected = (string) 'google.com';

        $uri = $this->uri->withHost('google.com');

        $actual = $uri->getHost();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::getPort.
     *
     * @return void
     */
    public function testGetPortMethod()
    {
        $expected = (integer) 500;

        $uri = $this->uri->withPort(500);

        $actual = $uri->getPort();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::getQuery.
     *
     * @return void
     */
    public function testGetQueryMethod()
    {
        $expected = (string) 'type=user';

        $uri = $this->uri->withQuery('type=user');

        $actual = $uri->getQuery();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::getFragment.
     *
     * @return void
     */
    public function testGetFragmentMethod()
    {
        $expected = (string) 'test';

        $uri = $this->uri->withFragment('test');

        $actual = $uri->getFragment();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::getPath.
     *
     * @return void
     */
    public function testGetPathMethod()
    {
        $expected = (string) '/test';

        $uri = $this->uri->withPath('/test');

        $actual = (string) $uri->getPath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UriInterface::__toString.
     *
     * @return void
     */
    public function testToStringMethod()
    {
        $expected = 'https://me@roug.in:400/about';

        $actual = $this->uri->__toString();

        $this->assertEquals($expected, $actual);
    }
}

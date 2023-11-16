<?php

namespace Rougin\Slytherin\Http;

/**
 * URI Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class UriTest extends \LegacyPHPUnit\TestCase
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

        $result = $uri->getScheme();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::getAuthority.
     *
     * @return void
     */
    public function testGetAuthorityMethod()
    {
        $expected = 'me@roug.in:400';

        $result = $this->uri->getAuthority();

        $this->assertEquals($expected, $result);
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

        $result = $uri->getUserInfo();

        $this->assertEquals($expected, $result);
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

        $result = $uri->getHost();

        $this->assertEquals($expected, $result);
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

        $result = $uri->getPort();

        $this->assertEquals($expected, $result);
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

        $result = $uri->getQuery();

        $this->assertEquals($expected, $result);
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

        $result = $uri->getFragment();

        $this->assertEquals($expected, $result);
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

        $result = (string) $uri->getPath();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UriInterface::__toString.
     *
     * @return void
     */
    public function testToStringMethod()
    {
        $expected = 'https://me@roug.in:400/about';

        $result = $this->uri->__toString();

        $this->assertEquals($expected, $result);
    }
}

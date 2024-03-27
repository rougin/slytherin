<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UriTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->uri = new Uri('https://me@roug.in:400/about');
    }

    /**
     * @return void
     */
    public function test_getting_url_scheme()
    {
        $expected = (string) 'http';

        $uri = $this->uri->withScheme('http');

        $actual = $uri->getScheme();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_authority_link()
    {
        $expected = 'me@roug.in:400';

        $actual = $this->uri->getAuthority();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_user_info()
    {
        $expected = (string) 'username:password';

        $uri = $this->uri->withUserInfo('username', 'password');

        $actual = $uri->getUserInfo();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_updating_hostname()
    {
        $expected = (string) 'google.com';

        $uri = $this->uri->withHost('google.com');

        $actual = $uri->getHost();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_the_url_port()
    {
        $expected = (int) 500;

        $uri = $this->uri->withPort(500);

        $actual = $uri->getPort();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_updating_the_query_params()
    {
        $expected = (string) 'type=user';

        $uri = $this->uri->withQuery('type=user');

        $actual = $uri->getQuery();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_updating_the_fragment()
    {
        $expected = (string) 'test';

        $uri = $this->uri->withFragment('test');

        $actual = $uri->getFragment();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_updating_the_url_path()
    {
        $expected = (string) '/test';

        $uri = $this->uri->withPath('/test');

        $actual = (string) $uri->getPath();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_converting_instance_to_string()
    {
        $expected = 'https://me@roug.in:400/about';

        $actual = $this->uri->__toString();

        $this->assertEquals($expected, $actual);
    }
}

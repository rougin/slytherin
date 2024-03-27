<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RequestTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->request = new Request;
    }

    /**
     * @return void
     */
    public function test_setting_a_http_method()
    {
        $expected = 'POST';

        $request = $this->request->withMethod($expected);

        $actual = $request->getMethod();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_a_request_target()
    {
        $expected = '/lorem-ipsum';

        $request = $this->request->withRequestTarget($expected);

        $actual = $request->getRequestTarget();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_an_uri_instance()
    {
        $expected = new Uri('https://www.google.com');

        $request = $this->request->withUri($expected);

        $actual = $request->getUri();

        $this->assertEquals($expected, $actual);
    }
}

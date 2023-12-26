<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * Request Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RequestTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * Sets up the request instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->request = new Request;
    }

    /**
     * Tests RequestInterface::getMethod.
     *
     * @return void
     */
    public function testGetMethodMethod()
    {
        $expected = 'POST';

        $request = $this->request->withMethod($expected);

        $actual = $request->getMethod();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests RequestInterface::getRequestTarget.
     *
     * @return void
     */
    public function testGetRequestTargetMethod()
    {
        $expected = '/lorem-ipsum';

        $request = $this->request->withRequestTarget($expected);

        $actual = $request->getRequestTarget();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests RequestInterface::getUri.
     *
     * @return void
     */
    public function testGetUriMethod()
    {
        $expected = new Uri('https://www.google.com');

        $request = $this->request->withUri($expected);

        $actual = $request->getUri();

        $this->assertEquals($expected, $actual);
    }
}

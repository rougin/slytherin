<?php

namespace Rougin\Slytherin\Http;

/**
 * Request Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RequestTest extends \Rougin\Slytherin\Testcase
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

        $result = $request->getMethod();

        $this->assertEquals($expected, $result);
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

        $result = $request->getRequestTarget();

        $this->assertEquals($expected, $result);
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

        $result = $request->getUri();

        $this->assertEquals($expected, $result);
    }
}

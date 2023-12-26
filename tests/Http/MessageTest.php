<?php

namespace Rougin\Slytherin\Http;

/**
 * Message Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class MessageTest extends \Rougin\Slytherin\Testcase
{
    /**
     * @var \Psr\Http\Message\MessageInterface
     */
    protected $message;

    /**
     * Sets up the message instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->message = new Message;
    }

    /**
     * Tests MessageInterface::getHeaderLine.
     *
     * @return void
     */
    public function testGetHeaderLineMethod()
    {
        $expected = '18';

        $message = $this->message->withHeader('age', '18');

        $actual = $message->getHeaderLine('age');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests MessageInterface::getHeader.
     *
     * @return void
     */
    public function testGetHeaderMethod()
    {
        $expected = array('18');

        $message = $this->message->withHeader('age', '18');

        $actual = $message->getHeader('age');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests MessageInterface::getHeaders.
     *
     * @return void
     */
    public function testGetHeadersMethod()
    {
        $expected = array('name' => array('John Doe'), 'age' => array('18'));

        $message = $this->message->withHeader('name', 'John Doe');

        $message = $message->withHeader('age', '18');

        $actual = $message->getHeaders();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests MessageInterface::getProtocolVersion.
     *
     * @return void
     */
    public function testGetProtocolVersionMethod()
    {
        $expected = (string) '1.2';

        $message = $this->message->withProtocolVersion($expected);

        $actual = $message->getProtocolVersion();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests MessageInterface::withAddedHeader.
     *
     * @return void
     */
    public function testWithAddedHeaderMethod()
    {
        $message = $this->message->withAddedHeader('age', '18');

        $this->assertTrue($message->hasHeader('age'));

        $expected = array('Rougin', 'Royce', 'Gutib');

        $message = $this->message->withAddedHeader('names', $expected);

        $actual = $message->getHeader('names');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests MessageInterface::withBody.
     *
     * @return void
     */
    public function testWithBodyMethod()
    {
        /** @var resource */
        $file = fopen('php://temp', 'r+');

        $expected = new Stream($file);

        $message = $this->message->withBody($expected);

        $actual = $message->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests MessageInterface::withoutAddedHeader.
     *
     * @return void
     */
    public function testWithoutAddedHeaderMethod()
    {
        $expected = array('name' => array('John Doe'));

        $message = $this->message->withHeader('name', 'John Doe');

        $message = $message->withHeader('age', '18');

        $message = $message->withoutHeader('age');

        $actual = $message->getHeaders();

        $this->assertEquals($expected, $actual);
    }
}

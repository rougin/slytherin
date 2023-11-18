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
        $expected = (integer) 18;

        $message = $this->message->withHeader('age', 18);

        $result = $message->getHeaderLine('age');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests MessageInterface::getHeader.
     *
     * @return void
     */
    public function testGetHeaderMethod()
    {
        $expected = array(18);

        $message = $this->message->withHeader('age', 18);

        $result = $message->getHeader('age');

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests MessageInterface::getHeaders.
     *
     * @return void
     */
    public function testGetHeadersMethod()
    {
        $expected = array('name' => array('John Doe'), 'age' => array(18));

        $message = $this->message->withHeader('name', 'John Doe');

        $message = $message->withHeader('age', 18);

        $result = $message->getHeaders();

        $this->assertEquals($expected, $result);
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

        $result = $message->getProtocolVersion();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests MessageInterface::withAddedHeader.
     *
     * @return void
     */
    public function testWithAddedHeaderMethod()
    {
        $message = $this->message->withAddedHeader('age', 18);

        $this->assertTrue($message->hasHeader('age'));

        $expected = array('Rougin', 'Royce', 'Gutib');

        $message = $this->message->withAddedHeader('names', $expected);

        $this->assertEquals($expected, $message->getHeader('names'));
    }

    /**
     * Tests MessageInterface::withBody.
     *
     * @return void
     */
    public function testWithBodyMethod()
    {
        $expected = new Stream(fopen('php://temp', 'r+'));

        $message = $this->message->withBody($expected);

        $this->assertEquals($expected, $message->getBody());
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

        $message = $message->withHeader('age', 18);

        $message = $message->withoutHeader('age');

        $result = $message->getHeaders();

        $this->assertEquals($expected, $result);
    }
}

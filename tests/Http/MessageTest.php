<?php

namespace Rougin\Slytherin\Http;

/**
 * Message Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Psr\Http\Message\MessageInterface
     */
    protected $message;

    /**
     * Sets up the message.
     *
     * @return void
     */
    public function setUp()
    {
        $this->message = new \Rougin\Slytherin\Http\Message;
    }

    /**
     * Tests getProtocolVersion() and withProtocolVersion().
     *
     * @return void
     */
    public function testProtocolVersion()
    {
        $expected = '1.2';
        $message  = $this->message->withProtocolVersion($expected);

        $this->assertEquals($expected, $message->getProtocolVersion());
    }

    /**
     * Tests methods related in manipulating headers.
     *
     * @return void
     */
    public function testHeaders()
    {
        $expected = array('name' => array('John Doe'), 'age' => array(18, 21));
        $headers  = array('name' => 'John Doe', 'age' => 18);
        $message  = $this->message;

        foreach ($headers as $key => $value) {
            $message = $message->withHeader($key, $value);
        }

        $message = $message->withAddedHeader('age', 21);

        $hasHeader    = $message->hasHeader('age');
        $headerEqual  = $message->getHeader('age') == $expected['age'];
        $headersEqual = $message->getHeaders() == $expected;
        $headerLine   = $message->getHeaderLine('age') == implode(',', $expected['age']);

        $message = $message->withoutHeader('address')->withoutHeader('age');

        $this->assertTrue($headersEqual && $hasHeader && $headerEqual && $headerLine);
    }

    /**
     * Tests withBody().
     *
     * @return void
     */
    public function testWithBody()
    {
        $expected = new \Rougin\Slytherin\Http\Stream;
        $message  = $this->message->withBody($expected);

        $this->assertEquals($expected, $message->getBody());
    }
}

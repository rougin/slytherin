<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class MessageTest extends Testcase
{
    /**
     * @var \Psr\Http\Message\MessageInterface
     */
    protected $message;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->message = new Message;
    }

    /**
     * @return void
     */
    public function test_getting_a_single_header_line()
    {
        $expected = '18';

        $message = $this->message->withHeader('age', '18');

        $actual = $message->getHeaderLine('age');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_a_single_header()
    {
        $expected = array('18');

        $message = $this->message->withHeader('age', '18');

        $actual = $message->getHeader('age');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_multiple_headers()
    {
        $expected = array('name' => array('John Doe'));

        $expected['age'] = array('18');

        $message = $this->message->withHeader('name', 'John Doe');

        $message = $message->withHeader('age', '18');

        $actual = $message->getHeaders();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_protocol_version()
    {
        $expected = (string) '1.2';

        $message = $this->message->withProtocolVersion($expected);

        $actual = $message->getProtocolVersion();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_adding_a_header()
    {
        $message = $this->message->withAddedHeader('age', '18');

        $this->assertTrue($message->hasHeader('age'));

        $expected = array('Rougin', 'Royce', 'Gutib');

        $message = $this->message->withAddedHeader('names', $expected);

        $actual = $message->getHeader('names');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_a_stream_body()
    {
        /** @var resource */
        $file = fopen('php://temp', 'r+');

        $expected = new Stream($file);

        $message = $this->message->withBody($expected);

        $actual = $message->getBody();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_excluding_an_existing_header()
    {
        $expected = array('name' => array('John Doe'));

        $message = $this->message->withHeader('name', 'John Doe');

        $message = $message->withHeader('age', '18');

        $message = $message->withoutHeader('age');

        $actual = $message->getHeaders();

        $this->assertEquals($expected, $actual);
    }
}

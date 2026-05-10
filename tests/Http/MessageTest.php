<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
    public function test_failed_if_added_header_name_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to add a header with an invalid name ---
        $this->message->withAddedHeader("name\r\n", 'value');
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_header_name_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to set a header with an invalid name ---
        $this->message->withHeader("name\r\n", 'value');
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_added()
    {
        $expect = array('Rougin', 'Royce', 'Gutib');

        // Add multiple values to the header -----------
        $message = $this->message->withAddedHeader('names', $expect);
        // ---------------------------------------------

        // Verify the header values are returned ---
        $actual = $message->getHeader('names');

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_exists()
    {
        // Add a header to the message -----------
        $message = $this->message->withAddedHeader('age', '18');
        // ---------------------------------------

        // Verify the header is present ---
        $this->assertTrue($message->hasHeader('age'));
        // --------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_line_retrieved()
    {
        $expect = '18';

        // Set the header on the message ------------
        $message = $this->message->withHeader('age', '18');
        // ------------------------------------------

        // Verify the header line is returned correctly ---
        $actual = $message->getHeaderLine('age');

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_removed()
    {
        $expect = array('name' => array('John Doe'));

        // Set multiple headers on the message ---------
        $message = $this->message->withHeader('name', 'John Doe');

        $message = $message->withHeader('age', '18');

        $message = $message->withoutHeader('age');
        // --------------------------------------------

        // Verify the header was excluded ---
        $actual = $message->getHeaders();

        $this->assertEquals($expect, $actual);
        // ----------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_retrieved()
    {
        $expect = array('18');

        // Set a header on the message ---------------
        $message = $this->message->withHeader('age', '18');
        // -------------------------------------------

        // Verify the header value is returned correctly ---
        $actual = $message->getHeader('age');

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_headers_retrieved()
    {
        $expect = array('name' => array('John Doe'));

        $expect['age'] = array('18');

        // Set multiple headers on the message ---------
        $message = $this->message->withHeader('name', 'John Doe');

        $message = $message->withHeader('age', '18');
        // ---------------------------------------------

        // Verify all headers are returned correctly ---
        $actual = $message->getHeaders();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_protocol_version_retrieved()
    {
        $expect = '1.2';

        // Update the protocol version ---------------
        $message = $this->message->withProtocolVersion($expect);
        // -------------------------------------------

        // Verify the version is returned correctly ---
        $actual = $message->getProtocolVersion();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_body_set()
    {
        /** @var resource */
        $file = fopen('php://temp', 'r+');

        $expect = new Stream($file);

        // Set the stream body on the message ----
        $message = $this->message->withBody($expect);
        // ---------------------------------------

        // Verify the body is returned correctly ---
        $actual = $message->getBody();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->message = new Message;
    }
}

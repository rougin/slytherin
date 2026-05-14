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
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_added_header_name_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doExpectException($expect);

        $this->self->withAddedHeader("name\r\n", 'value');
    }

    /**
     * @return void
     */
    public function test_failed_if_header_name_invalid()
    {
        $expect = 'InvalidArgumentException';

        $this->doExpectException($expect);

        $this->self->withHeader("name\r\n", 'value');
    }

    /**
     * @return void
     */
    public function test_passed_if_header_added()
    {
        $expect = array('Rougin', 'Royce', 'Gutib');

        $self = $this->self->withAddedHeader('names', $expect);

        // Verify the header values are returned ---
        $actual = $self->getHeader('names');

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_check_is_case_insensitive()
    {
        $self = $this->self->withAddedHeader('age', '18');

        $this->assertTrue($self->hasHeader('Age'));
    }

    /**
     * @return void
     */
    public function test_passed_if_header_exists()
    {
        $self = $this->self->withAddedHeader('age', '18');

        $this->assertTrue($self->hasHeader('age'));
    }

    /**
     * @return void
     */
    public function test_passed_if_header_line_ignores_case()
    {
        $expect = 'test';

        $self = $this->self->withHeader('names', 'test');

        $actual = $self->getHeaderLine('NAMES');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_header_line_found()
    {
        $expect = '18';

        // Set the header on the message ------------
        $self = $this->self->withHeader('age', '18');
        // ------------------------------------------

        // Verify the header line is returned correctly ---
        $actual = $self->getHeaderLine('age');

        $this->assertEquals($expect, $actual);
        // ------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_removed()
    {
        $expect = array('name' => array('John Doe'));

        // Set multiple headers on the message -------------
        $self = $this->self->withHeader('name', 'John Doe');

        $self = $self->withHeader('age', '18');

        $self = $self->withoutHeader('age');
        // -------------------------------------------------

        // Verify the header was excluded ---
        $actual = $self->getHeaders();

        $this->assertEquals($expect, $actual);
        // ----------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_header_found()
    {
        $expect = array('18');

        // Set a header on the message --------------
        $self = $this->self->withHeader('age', '18');
        // ------------------------------------------

        // Verify the header value is returned correctly ---
        $actual = $self->getHeader('age');

        $this->assertEquals($expect, $actual);
        // -------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_headers_found()
    {
        $expect = array('name' => array('John Doe'));

        $expect['age'] = array('18');

        // Set multiple headers on the message -------------
        $self = $this->self->withHeader('name', 'John Doe');

        $self = $self->withHeader('age', '18');
        // -------------------------------------------------

        // Verify all headers are returned correctly ---
        $actual = $self->getHeaders();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_protocol_version_found()
    {
        $expect = '1.2';

        // Update the protocol version -------------------
        $self = $this->self->withProtocolVersion($expect);
        // -----------------------------------------------

        // Verify the version is returned correctly ---
        $actual = $self->getProtocolVersion();

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

        // Set the stream body on the message ---
        $self = $this->self->withBody($expect);
        // --------------------------------------

        // Verify the body is returned correctly ---
        $actual = $self->getBody();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Message;
    }
}

<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class StreamTest extends Testcase
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_stream_is_closed()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Close the stream first ---
        $this->self->close();
        // --------------------------

        // Attempt to read after closing ---
        $this->self->getContents();
        // ---------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_not_readable()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Create a write-only stream ---
        /** @var resource */
        $file = fopen($this->file, 'w');

        $self = new Stream($file);
        // ------------------------------

        // Attempt to read from a non-readable stream ---
        echo $self->getContents();
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_position_detached()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Detach the stream before seeking ---
        $self = new Stream($this->newFile());

        $self->detach() && $self->seek(2);
        // ------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_read_exceeds_size()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Attempt to read beyond the stream size ---
        $self = new Stream($this->newFile());

        $self->read(55);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_tell_detached()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Detach the stream before checking position ---
        $self = new Stream($this->newFile());

        $self->detach() && $self->tell();
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_write_readonly()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Create a read-only stream -----------
        $self = new Stream($this->newFile('r'));
        // -------------------------------------

        // Attempt to write to it ---
        $self->write('Hello');

        $self->getContents();
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_stream_has_null_size()
    {
        // Create an empty stream ---
        $self = new Stream;
        // --------------------------

        // Verify the size is null ---
        $actual = $self->getSize();

        $this->assertNull($actual);
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_eof_detected_after_full_read()
    {
        $self = new Stream($this->newFile('w+'));

        $self->write('Hello');

        $self->seek(0);

        $self->getContents();

        $this->assertTrue($self->eof());
    }

    /**
     * @return void
     */
    public function test_passed_if_end_of_file_returned()
    {
        // Create a stream from a new file ---
        $self = new Stream($this->newFile());
        // -----------------------------------

        // Verify the end-of-file flag is false ---
        $this->assertFalse($self->eof());
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_readable_is_detected()
    {
        $self = new Stream($this->newFile('r'));

        $this->assertTrue($self->isReadable());
    }

    /**
     * @return void
     */
    public function test_passed_if_rewind_resets_position()
    {
        $expect = 0;

        $self = new Stream($this->newFile());

        $self->seek(4);

        $self->rewind();

        $actual = $self->tell();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_seekable_is_detected()
    {
        $self = new Stream($this->newFile('r'));

        $this->assertTrue($self->isSeekable());
    }

    /**
     * @return void
     */
    public function test_passed_if_to_string_rewinds_stream()
    {
        $expect = 'This is a text from a template.';

        $self = new Stream($this->newFile('w+'));

        $self->write($expect);

        $self->seek(4);

        $actual = $self->__toString();

        $this->assertEquals($expect, $actual);
    }

    /**
     * @return void
     */
    public function test_passed_if_writable_is_detected()
    {
        $self = new Stream($this->newFile());

        $this->assertTrue($self->isWritable());
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_content_found()
    {
        $expect = 'This is a text from a template.';

        // Verify the stream content is returned correctly ---
        $resource = $this->self->__toString();

        $this->assertEquals($expect, $resource);
        // ---------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_detached()
    {
        $expect = 'stream';

        $actual = null;

        // Detach the underlying resource ---
        $resource = $this->self->detach();
        // ----------------------------------

        // Verify the resource type is still valid ---
        if ($resource)
        {
            $actual = get_resource_type($resource);
        }

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_metadata_found()
    {
        // Create a stream from a new file ---
        $file = $this->newFile();

        $self = new Stream($file);
        // -----------------------------------

        // Verify the metadata matches the file's data ---
        $expect = stream_get_meta_data($file);

        $actual = $self->getMetadata();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_position_set()
    {
        $expect = 2;

        // Create a stream and set its position ---
        $self = new Stream($this->newFile());

        $self->seek($expect);
        // ----------------------------------------

        // Verify the position was set correctly ---
        $actual = $self->tell();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_read()
    {
        $expect = 'This';

        // Read the first four bytes of the stream ---
        $actual = $this->self->read(4);
        // -------------------------------------------

        // Verify the correct content was read ---
        $this->assertEquals($expect, $actual);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_size_found()
    {
        $expect = 31;

        // Verify the stream size matches the fixture file ---
        $actual = $this->self->getSize();

        $this->assertEquals($expect, $actual);
        // ---------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_written()
    {
        $expect = 'Lorem ipsum dolor sit amet elit.';

        // Write content to the stream ----------
        $self = new Stream($this->newFile('w+'));

        $self->write($expect);
        // --------------------------------------

        // Verify the content was written correctly ---
        $self = new Stream($this->newFile('r'));

        $actual = $self->getContents();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        /** @var resource */
        $file = fopen("$root/Templates/test.php", 'r');

        $this->self = new Stream($file);

        $this->file = "$root/Templates/new-test.php";
    }

    /**
     * @param string $mode
     *
     * @return resource
     */
    protected function newFile($mode = 'w')
    {
        /** @var resource */
        return fopen($this->file, $mode);
    }
}

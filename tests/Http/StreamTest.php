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
    protected $filepath;

    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    protected $stream;

    /**
     * @return void
     */
    public function test_failed_if_stream_content_readable()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Create a write-only stream ---------------
        /** @var resource */
        $file = fopen($this->filepath, 'w');

        $stream = new Stream($file);
        // ------------------------------------------

        // Attempt to read from a non-readable stream ---
        echo $stream->getContents();
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_is_closed()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Close the stream first ----
        $this->stream->close();
        // ---------------------------

        // Attempt to read after closing ---
        $this->stream->getContents();
        // --------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_position_detached()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Detach the stream before seeking ---
        $stream = new Stream($this->newFile());

        $stream->detach() && $stream->seek(2);
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
        $stream = new Stream($this->newFile());

        $stream->read(55);
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
        $stream = new Stream($this->newFile());

        $stream->detach() && $stream->tell();
        // --------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_write_readonly()
    {
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Create a read-only stream --------
        $stream = new Stream($this->newFile('r'));
        // -----------------------------------

        // Attempt to write to it ---
        $stream->write('Hello') && $stream->getContents();
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_stream_size_null()
    {
        // Create an empty stream ------------------
        $stream = new Stream;
        // -----------------------------------------

        // Verify the size is null ---
        $actual = $stream->getSize();

        $this->assertNull($actual);
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_end_of_file_returned()
    {
        // Create a stream from a new file ---
        $stream = new Stream($this->newFile());
        // -----------------------------------

        // Verify the end-of-file flag is false ---
        $this->assertFalse($stream->eof());
        // ----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_content_retrieved()
    {
        $expect = 'This is a text from a template.';

        // Verify the stream content is returned correctly ---
        $resource = $this->stream->__toString();

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

        // Detach the underlying resource ----
        $resource = $this->stream->detach();
        // -----------------------------------

        // Verify the resource type is still valid ---
        if ($resource)
        {
            $actual = get_resource_type($resource);
        }

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_metadata_retrieved()
    {
        // Create a stream from a new file -----
        $file = $this->newFile();

        $stream = new Stream($file);
        // -------------------------------------

        // Verify the metadata matches the file's data ---
        $expect = stream_get_meta_data($file);

        $actual = $stream->getMetadata();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_position_set()
    {
        $expect = 2;

        // Create a stream and set its position ----
        $stream = new Stream($this->newFile());

        $stream->seek($expect);
        // -----------------------------------------

        // Verify the position was set correctly ---
        $actual = $stream->tell();

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
        $actual = $this->stream->read(4);
        // -------------------------------------------

        // Verify the correct content was read ---
        $this->assertEquals($expect, $actual);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_size_retrieved()
    {
        $expect = 31;

        // Verify the stream size matches the fixture file ---
        $actual = $this->stream->getSize();

        $this->assertEquals($expect, $actual);
        // --------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_written()
    {
        $expect = 'Lorem ipsum dolor sit amet elit.';

        // Write content to the stream ---------------
        $stream = new Stream($this->newFile('w+'));

        $stream->write($expect);
        // -------------------------------------------

        // Verify the content was written correctly -----------------
        $stream = new Stream($this->newFile('r'));

        $actual = $stream->getContents();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        /** @var resource */
        $file = fopen("$root/Templates/test.php", 'r');

        $this->stream = new Stream($file);

        $this->filepath = "$root/Templates/new-test.php";
    }

    /**
     * @param string $mode
     *
     * @return resource
     */
    protected function newFile($mode = 'w')
    {
        /** @var resource */
        return fopen($this->filepath, $mode);
    }
}

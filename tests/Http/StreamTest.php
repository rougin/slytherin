<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
    protected function doSetUp()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        /** @var resource */
        $file = fopen("$root/Templates/test.php", 'r');

        $this->stream = new Stream($file);

        $this->filepath = "$root/Templates/new-test.php";
    }

    /**
     * @return void
     */
    public function test_closing_the_contents()
    {
        $this->setExpectedException('RuntimeException');

        $this->stream->close();

        $this->stream->getContents();
    }

    /**
     * @return void
     */
    public function test_detaching_the_stream()
    {
        $expected = 'stream';

        $actual = null;

        $resource = $this->stream->detach();

        if ($resource)
        {
            $actual = get_resource_type($resource);
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_end_of_file()
    {
        $stream = new Stream($this->newFile());

        $this->assertFalse($stream->eof());
    }

    /**
     * @return void
     */
    public function test_getting_stream_content()
    {
        $expected = 'This is a text from a template.';

        $resource = (string) $this->stream->__toString();

        $this->assertEquals($expected, $resource);
    }

    /**
     * @return void
     */
    public function test_getting_stream_content_with_an_error()
    {
        $this->setExpectedException('RuntimeException');

        /** @var resource */
        $file = fopen($this->filepath, 'w');

        $stream = new Stream($file);

        echo $stream->getContents();
    }

    /**
     * @return void
     */
    public function test_getting_metadata()
    {
        $file = $this->newFile();

        $stream = new Stream($file);

        $expected = stream_get_meta_data($file);

        $actual = $stream->getMetadata();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_stream_size()
    {
        $expected = (int) 31;

        $actual = $this->stream->getSize();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_stream_size_if_stream_is_empty()
    {
        $stream = new Stream;

        $actual = $stream->getSize();

        $this->assertNull($actual);
    }

    /**
     * @return void
     */
    public function test_reading_stream()
    {
        $expected = (string) 'This';

        $actual = (string) $this->stream->read(4);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_reading_stream_with_an_error()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream($this->newFile());

        $stream->read(55);
    }

    /**
     * @return void
     */
    public function test_setting_position()
    {
        $expected = (int) 2;

        $stream = new Stream($this->newFile());

        $stream->seek($expected);

        $actual = $stream->tell();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_setting_position_with_an_error()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream($this->newFile());

        $stream->detach() && $stream->seek(2);
    }

    /**
     * @return void
     */
    public function test_getting_current_position_with_an_error()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream($this->newFile());

        $stream->detach() && $stream->tell();
    }

    /**
     * @return void
     */
    public function test_writing_the_stream()
    {
        $expected = 'Lorem ipsum dolor sit amet elit.';

        $stream = new Stream($this->newFile('w+'));

        $stream->write($expected);

        $stream = new Stream($this->newFile('r'));

        $actual = $stream->getContents();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_writing_the_stream_with_an_error()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream($this->newFile('r'));

        $stream->write('Hello') && $stream->getContents();
    }

    /**
     * @param  string $mode
     * @return resource
     */
    protected function newFile($mode = 'w')
    {
        /** @var resource */
        return fopen($this->filepath, $mode);
    }
}

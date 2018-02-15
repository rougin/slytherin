<?php

namespace Rougin\Slytherin\Http;

/**
 * Stream Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class StreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var resource
     */
    protected $file;

    /**
     * @var string
     */
    protected $filepath;

    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    protected $stream;

    /**
     * Sets up the stream instance.
     *
     * @return void
     */
    public function setUp()
    {
        $root = (string) str_replace('Http', 'Fixture', __DIR__);

        $this->filepath = (string) $root . '/Templates/new-test.php';

        $this->file = fopen($root . '/Templates/test.php', 'r');

        $this->stream = new Stream($this->file);
    }

    /**
     * Tests StreamInterface::close.
     *
     * @return void
     */
    public function testCloseMethod()
    {
        $this->setExpectedException('RuntimeException');

        $this->stream->close();

        $this->stream->getContents();
    }

    /**
     * Tests StreamInterface::detach.
     *
     * @return void
     */
    public function testDetachMethod()
    {
        $expected = 'stream';

        $resource = $this->stream->detach();

        $result = get_resource_type($resource);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests StreamInterface::eof.
     *
     * @return void
     */
    public function testEofMethod()
    {
        $stream = new Stream(fopen($this->filepath, 'w'));

        $this->assertFalse($stream->eof());
    }

    /**
     * Tests StreamInterface::getContents.
     *
     * @return void
     */
    public function testGetContentsMethod()
    {
        $expected = 'This is a text from a template.';

        $resource = (string) $this->stream->__toString();

        $this->assertEquals($expected, $resource);
    }

    /**
     * Tests StreamInterface::getContents with an exception.
     *
     * @return void
     */
    public function testGetContentsMethodWithException()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream(fopen($this->filepath, 'w'));

        $stream->getContents()->__toString();
    }

    /**
     * Tests StreamInterface::getMetadata.
     *
     * @return void
     */
    public function testGetMetadataMethod()
    {
        $file = fopen($this->filepath, 'r');

        $expected = stream_get_meta_data($file);

        $stream = new Stream($file);

        $result = $stream->getMetadata();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests StreamInterface::getSize.
     *
     * @return void
     */
    public function testGetSizeMethod()
    {
        $expected = (integer) 31;

        $result = $this->stream->getSize();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests StreamInterface::read.
     *
     * @return void
     */
    public function testReadMethod()
    {
        $expected = (string) 'This';

        $result = (string) $this->stream->read(4);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests StreamInterface::read with an exception.
     *
     * @return void
     */
    public function testReadMethodWithException()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream(fopen($this->filepath, 'w'));

        $stream->read(55);
    }

    /**
     * Tests StreamInterface::seek.
     *
     * @return void
     */
    public function testSeekMethod()
    {
        $expected = (integer) 2;

        $stream = new Stream(fopen($this->filepath, 'w'));

        $stream->seek($expected);

        $result = $stream->tell();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests StreamInterface::seek with an exception.
     *
     * @return void
     */
    public function testSeekMethodWithException()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream(fopen($this->filepath, 'w'));

        $stream->detach() && $stream->seek(2);
    }

    /**
     * Tests StreamInterface::tell with an exception.
     *
     * @return void
     */
    public function testTellMethodWithException()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream(fopen($this->filepath, 'w'));

        $stream->detach() && $stream->tell();
    }

    /**
     * Tests StreamInterface::write.
     *
     * @return void
     */
    public function testWriteMethod()
    {
        $expected = 'Lorem ipsum dolor sit amet elit.';

        $stream = new Stream(fopen($this->filepath, 'w+'));

        $stream->write($expected);

        $stream = new Stream(fopen($this->filepath, 'r'));

        $result = $stream->getContents();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests StreamInterface::write with an exception.
     *
     * @return void
     */
    public function testWriteMethodWithException()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream(fopen($this->filepath, 'r'));

        $stream->write('Hello') && $stream->getContents();
    }
}

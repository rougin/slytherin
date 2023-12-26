<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * Stream Test
 *
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
     * Sets up the stream instance.
     *
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
        $expected = 'stream'; $actual = null;

        $resource = $this->stream->detach();

        if ($resource)
        {
            $actual = get_resource_type($resource);
        }

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests StreamInterface::eof.
     *
     * @return void
     */
    public function testEofMethod()
    {
        $stream = new Stream($this->newFile());

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

        /** @var resource */
        $file = fopen($this->filepath, 'w');

        $stream = new Stream($file);

        echo $stream->getContents();
    }

    /**
     * Tests StreamInterface::getMetadata.
     *
     * @return void
     */
    public function testGetMetadataMethod()
    {
        $file = $this->newFile();

        $stream = new Stream($file);

        $expected = stream_get_meta_data($file);

        $actual = $stream->getMetadata();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests StreamInterface::getSize.
     *
     * @return void
     */
    public function testGetSizeMethod()
    {
        $expected = (integer) 31;

        $actual = $this->stream->getSize();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests StreamInterface::read.
     *
     * @return void
     */
    public function testReadMethod()
    {
        $expected = (string) 'This';

        $actual = (string) $this->stream->read(4);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests StreamInterface::read with an exception.
     *
     * @return void
     */
    public function testReadMethodWithException()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream($this->newFile());

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

        $stream = new Stream($this->newFile());

        $stream->seek($expected);

        $actual = $stream->tell();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests StreamInterface::seek with an exception.
     *
     * @return void
     */
    public function testSeekMethodWithException()
    {
        $this->setExpectedException('RuntimeException');

        $stream = new Stream($this->newFile());

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

        $stream = new Stream($this->newFile());

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

        $stream = new Stream($this->newFile('w+'));

        $stream->write($expected);

        $stream = new Stream($this->newFile('r'));

        $actual = $stream->getContents();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests StreamInterface::write with an exception.
     *
     * @return void
     */
    public function testWriteMethodWithException()
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

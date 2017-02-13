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
     * @var \Psr\Http\Message\StreamInterface
     */
    protected $stream;

    /**
     * Sets up the stream.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Psr\Http\Message\StreamInterface')) {
            $this->markTestSkipped('PSR-7 is not installed.');
        }

        $this->file   = fopen(__DIR__ . '/../Fixture/Templates/test.php', 'r');
        $this->stream = new \Rougin\Slytherin\Http\Stream($this->file);
    }

    /**
     * Tests getContents().
     *
     * @return void
     */
    public function testGetContents()
    {
        $this->assertEquals('This is a text from a template.', $this->stream->getContents());
    }

    /**
     * Tests getContents() with exception.
     *
     * @return void
     */
    public function testGetContentsException()
    {
        $this->setExpectedException('RuntimeException');

        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'w');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $stream->getContents();
    }

    /**
     * Tests read().
     *
     * @return void
     */
    public function testRead()
    {
        $this->assertEquals('This', $this->stream->read(4));
    }

    /**
     * Tests read() with exception.
     *
     * @return void
     */
    public function testReadException()
    {
        $this->setExpectedException('RuntimeException');

        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'w');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $stream->detach();
        $stream->read(4);
    }

    /**
     * Tests detach().
     *
     * @return void
     */
    public function testDetach()
    {
        $resource = $this->stream->detach();

        $this->assertEquals('stream', get_resource_type($resource));
    }

    /**
     * Tests close().
     *
     * @return void
     */
    public function testClose()
    {
        $this->stream->close();

        $this->assertTrue(true);
    }

    /**
     * Tests getSize().
     *
     * @return void
     */
    public function testGetSize()
    {
        $this->assertEquals(31, $this->stream->getSize());
    }

    /**
     * Tests seek() and tell().
     *
     * @return void
     */
    public function testSeekAndTell()
    {
        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'w');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $stream->seek(2);

        $this->assertEquals(2, $stream->tell());
    }

    /**
     * Tests seek() after detach().
     *
     * @return void
     */
    public function testSeekOnDetached()
    {
        $this->setExpectedException('RuntimeException');

        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'w');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $stream->detach();
        $stream->seek(2);
    }

    /**
     * Tests tell() after detach().
     *
     * @return void
     */
    public function testTellOnDetached()
    {
        $this->setExpectedException('RuntimeException');

        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'w');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $stream->detach();
        $stream->tell();
    }

    /**
     * Tests eof().
     *
     * @return void
     */
    public function testEof()
    {
        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'w');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $this->assertEquals(false, $stream->eof());
    }

    /**
     * Tests write() and getContents() with exception.
     *
     * @return void
     */
    public function testWriteAndGetContentsException()
    {
        $this->setExpectedException('RuntimeException');

        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'r');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $stream->write('Hello world');

        $stream->getContents();
    }

    /**
     * Tests empty stream.
     *
     * @return void
     */
    public function testEmptyStream()
    {
        $stream = new \Rougin\Slytherin\Http\Stream;

        $this->assertEmpty((string) $stream);
    }

    /**
     * Tests getMetadata().
     *
     * @return void
     */
    public function testGetMetadata()
    {
        $file   = fopen(__DIR__ . '/../Fixture/Templates/new-test.php', 'r');
        $stream = new \Rougin\Slytherin\Http\Stream($file);

        $this->assertEquals(stream_get_meta_data($file), $stream->getMetadata());
    }
}

<?php

namespace Rougin\Slytherin\Http;

/**
 * Uploaded File Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class UploadedFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Psr\Http\Message\UploadedFileInterface
     */
    protected $uploaded;

    /**
     * Sets up the uploaded file instance.
     *
     * @return void
     */
    public function setUp()
    {
        $root = (string) str_replace('Http', 'Fixture', __DIR__);

        $filepath = (string) $root . '/Templates/new-test.php';

        file_put_contents($filepath, 'Hello world');

        $this->uploaded = new UploadedFile($filepath, 400, 0, 'new-test.php', 'text/plain');
    }

    /**
     * Tests UploadedFileInterface::getSize.
     *
     * @return void
     */
    public function testGetSizeMethod()
    {
        $expected = (integer) 400;

        $result = (integer) $this->uploaded->getSize();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UploadedFileInterface::getError.
     *
     * @return void
     */
    public function testGetErrorMethod()
    {
        $expected = (integer) UPLOAD_ERR_OK;

        $result = $this->uploaded->getError();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UploadedFileInterface::getClientFilename.
     *
     * @return void
     */
    public function testGetClientFilenameMethod()
    {
        $expected = (string) 'new-test.php';

        $result = $this->uploaded->getClientFilename();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UploadedFileInterface::getClientMediaType.
     *
     * @return void
     */
    public function testGetClientMediaTypeMethod()
    {
        $expected = (string) 'text/plain';

        $result = $this->uploaded->getClientMediaType();

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests UplaodedFileInterface::getStream.
     *
     * @return void
     */
    public function testGetStreamMethod()
    {
        $expected = 'Rougin\Slytherin\Http\Stream';

        $result = $this->uploaded->getStream();

        $this->assertInstanceOf($expected, $result);
    }

    /**
     * Tests UploadedFileInterface::moveTo.
     *
     * @return void
     */
    public function testMoveToMethod()
    {
        $root = (string) str_replace('Http', 'Fixture', __DIR__);

        $target = (string) $root . '/Templates/new.php';

        $this->uploaded->moveTo($target);

        $this->assertFileExists($target);

        file_exists($target) && unlink($target);
    }
}

<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * Uploaded File Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class UploadedFileTest extends Testcase
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
    protected function doSetUp()
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
        $actual = $this->uploaded->getSize();

        $expected = 400;

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UploadedFileInterface::getError.
     *
     * @return void
     */
    public function testGetErrorMethod()
    {
        $actual = $this->uploaded->getError();

        $expected = UPLOAD_ERR_OK;

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UploadedFileInterface::getClientFilename.
     *
     * @return void
     */
    public function testGetClientFilenameMethod()
    {
        $expected = (string) 'new-test.php';

        $actual = $this->uploaded->getClientFilename();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UploadedFileInterface::getClientMediaType.
     *
     * @return void
     */
    public function testGetClientMediaTypeMethod()
    {
        $expected = (string) 'text/plain';

        $actual = $this->uploaded->getClientMediaType();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests UplaodedFileInterface::getStream.
     *
     * @return void
     */
    public function testGetStreamMethod()
    {
        $expected = 'Rougin\Slytherin\Http\Stream';

        $actual = $this->uploaded->getStream();

        $this->assertInstanceOf($expected, $actual);
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

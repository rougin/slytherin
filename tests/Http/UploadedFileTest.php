<?php

namespace Rougin\Slytherin\Http;

/**
 * Uploaded File Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class UploadedFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Psr\Http\Message\UploadedFileInterface
     */
    protected $uploadedFile;

    /**
     * Sets up the uploadedFile.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Psr\Http\Message\UploadedFileInterface')) {
            $this->markTestSkipped('PSR-7 is not installed.');
        }

        $filePath = __DIR__ . '/../Fixture/Templates/new-test.php';

        file_put_contents($filePath, 'Hello world');

        $this->uploadedFile = new \Rougin\Slytherin\Http\UploadedFile($filePath, 400, UPLOAD_ERR_OK, 'new-test.php', 'text/plain');
    }

    /**
     * Tests getSize().
     *
     * @return void
     */
    public function testGetSize()
    {
        $this->assertEquals(400, $this->uploadedFile->getSize());
    }

    /**
     * Tests getError().
     *
     * @return void
     */
    public function testGetError()
    {
        $this->assertEquals(UPLOAD_ERR_OK, $this->uploadedFile->getError());
    }

    /**
     * Tests getClientFilename().
     *
     * @return void
     */
    public function testGetClientFilename()
    {
        $this->assertEquals('new-test.php', $this->uploadedFile->getClientFilename());
    }

    /**
     * Tests getClientMediaType().
     *
     * @return void
     */
    public function testGetClientMediaType()
    {
        $this->assertEquals('text/plain', $this->uploadedFile->getClientMediaType());
    }

    /**
     * Tests getStream().
     *
     * @return void
     */
    public function testGetStream()
    {
        $this->assertInstanceOf('Rougin\Slytherin\Http\Stream', $this->uploadedFile->getStream());
    }

    /**
     * Tests moveTo().
     *
     * @return void
     */
    public function testMoveTo()
    {
        $targetPath = __DIR__ . '/../Fixture/Templates/new.php';

        $this->uploadedFile->moveTo($targetPath);

        $this->assertFileExists($targetPath);

        unlink($targetPath);
    }
}

<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
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
     * @return void
     */
    public function test_getting_file_size()
    {
        $actual = $this->uploaded->getSize();

        $expected = 400;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_file_error_if_any()
    {
        $actual = $this->uploaded->getError();

        $expected = UPLOAD_ERR_OK;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_file_name()
    {
        $expected = (string) 'new-test.php';

        $actual = $this->uploaded->getClientFilename();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_media_type()
    {
        $expected = (string) 'text/plain';

        $actual = $this->uploaded->getClientMediaType();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_stream_body()
    {
        $expected = 'Rougin\Slytherin\Http\Stream';

        $actual = $this->uploaded->getStream();

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_moving_the_uploaded_file()
    {
        $root = (string) str_replace('Http', 'Fixture', __DIR__);

        $target = (string) $root . '/Templates/new.php';

        $this->uploaded->moveTo($target);

        $this->assertFileExists($target);

        file_exists($target) && unlink($target);
    }
}

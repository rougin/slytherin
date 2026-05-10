<?php

namespace Rougin\Slytherin\Http;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
    public function test_getting_file_error_if_any()
    {
        $actual = $this->uploaded->getError();

        $expected = UPLOAD_ERR_OK;

        $this->assertEquals($expected, $actual);
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
    public function test_getting_stream_after_move_throws_exception()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $target = $root . '/Templates/new.php';

        $this->uploaded->moveTo($target);

        file_exists($target) && unlink($target);

        $this->doSetExpectedException('RuntimeException');

        $this->uploaded->getStream();
    }

    /**
     * @return void
     */
    public function test_getting_the_file_name()
    {
        $expected = 'file-test.php';

        $actual = $this->uploaded->getClientFilename();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_the_media_type()
    {
        $expected = 'text/plain';

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
    public function test_moving_file_twice_throws_exception()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $target = $root . '/Templates/new.php';

        $this->uploaded->moveTo($target);

        file_exists($target) && unlink($target);

        $this->doSetExpectedException('RuntimeException');

        $this->uploaded->moveTo($target);
    }

    /**
     * @return void
     */
    public function test_moving_file_with_invalid_target_throws_exception()
    {
        $this->doSetExpectedException('InvalidArgumentException');

        $this->uploaded->moveTo('');
    }

    /**
     * @return void
     */
    public function test_moving_file_with_upload_error_throws_exception()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $filepath = $root . '/Templates/file-test.php';

        $uploaded = new UploadedFile($filepath, 400, UPLOAD_ERR_NO_FILE, 'test.php', 'text/plain');

        $this->doSetExpectedException('RuntimeException');

        $uploaded->moveTo($root . '/Templates/test.php');
    }

    /**
     * @return void
     */
    public function test_moving_the_uploaded_file()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $target = $root . '/Templates/new.php';

        $this->uploaded->moveTo($target);

        $this->assertFileExists($target);

        file_exists($target) && unlink($target);
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $filepath = $root . '/Templates/file-test.php';

        file_put_contents($filepath, 'Hello world');

        $this->uploaded = new UploadedFile($filepath, 400, 0, 'file-test.php', 'text/plain');
    }
}

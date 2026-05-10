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
    public function test_failed_if_file_moved_twice()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $target = $root . '/Templates/new.php';

        // Move the file to the target path first ---
        $this->uploaded->moveTo($target);

        file_exists($target) && unlink($target);
        // ------------------------------------------

        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Attempt to move the same file again ---
        $this->uploaded->moveTo($target);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_move_target_empty()
    {
        $expect = 'InvalidArgumentException';

        $this->doSetExpectedException($expect);

        // Attempt to move to an empty target path ---
        $this->uploaded->moveTo('');
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_move_upload_error()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $filepath = $root . '/Templates/file-test.php';

        // Create an uploaded file with an error code ----
        $uploaded = new UploadedFile($filepath, 400, UPLOAD_ERR_NO_FILE, 'test.php', 'text/plain');
        // -----------------------------------------------

        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Attempt to move a file with an upload error ---
        $uploaded->moveTo($root . '/Templates/test.php');
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_stream_after_move()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $target = $root . '/Templates/new.php';

        // Move the file to the target path first ---
        $this->uploaded->moveTo($target);

        file_exists($target) && unlink($target);
        // ------------------------------------------

        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Attempt to get the stream after moving ---
        $this->uploaded->getStream();
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_file_error_retrieved()
    {
        $expect = UPLOAD_ERR_OK;

        // Verify the file error code is returned ---
        $actual = $this->uploaded->getError();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_file_moved()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $target = $root . '/Templates/new.php';

        // Move the uploaded file to the target path ---
        $this->uploaded->moveTo($target);
        // ---------------------------------------------

        // Verify the file was moved successfully ---
        $this->assertFileExists($target);

        file_exists($target) && unlink($target);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_file_name_retrieved()
    {
        $expect = 'file-test.php';

        // Verify the client filename is returned correctly ---
        $actual = $this->uploaded->getClientFilename();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_file_size_retrieved()
    {
        $expect = 400;

        // Verify the file size is returned correctly ---
        $actual = $this->uploaded->getSize();

        $this->assertEquals($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_media_type_retrieved()
    {
        $expect = 'text/plain';

        // Verify the media type is returned correctly ---
        $actual = $this->uploaded->getClientMediaType();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_body_retrieved()
    {
        $expect = 'Rougin\Slytherin\Http\Stream';

        // Verify the stream body is returned correctly ---
        $actual = $this->uploaded->getStream();

        $this->assertInstanceOf($expect, $actual);
        // -----------------------------------------------
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

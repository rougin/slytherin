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
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_file_moved_twice()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $target = $root . '/Templates/new.php';

        // Move the file to the target path first ---
        $this->self->moveTo($target);

        file_exists($target) && unlink($target);
        // ------------------------------------------

        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        // Attempt to move the same file again ---
        $this->self->moveTo($target);
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
        $this->self->moveTo('');
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_move_upload_error()
    {
        // Create an uploaded file with an error code ---
        $root = str_replace('Http', 'Fixture', __DIR__);

        $filepath = $root . '/Templates/file-test.php';
        // ----------------------------------------------

        $self = new UploadedFile($filepath, 400, UPLOAD_ERR_NO_FILE, 'test.php', 'text/plain');

        // Attempt to move a file with an upload error ---
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        $self->moveTo($root . '/Templates/test.php');
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
        $this->self->moveTo($target);

        file_exists($target) && unlink($target);
        // ------------------------------------------

        // Attempt to get the stream after moving ---
        $expect = 'RuntimeException';

        $this->doSetExpectedException($expect);

        $this->self->getStream();
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_file_error_retrieved()
    {
        $expect = UPLOAD_ERR_OK;

        // Verify the file error code is returned ---
        $actual = $this->self->getError();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_file_moved()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        // Move the uploaded file to the target path ---
        $target = $root . '/Templates/new.php';

        $this->self->moveTo($target);
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

        // Verify the client filename is returned ---
        $actual = $this->self->getClientFilename();

        $this->assertEquals($expect, $actual);
        // ------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_file_size_retrieved()
    {
        $expect = 400;

        // Verify the file size is returned correctly ---
        $actual = $this->self->getSize();

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
        $actual = $this->self->getClientMediaType();

        $this->assertEquals($expect, $actual);
        // -----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_move_to_accepts_relative_path()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        chdir($root . '/Templates');

        $target = 'new.php';

        $this->self->moveTo($target);

        chdir(__DIR__);

        $targetPath = $root . '/Templates/' . $target;

        $this->assertFileExists($targetPath);

        file_exists($targetPath) && unlink($targetPath);
    }

    /**
     * @return void
     */
    public function test_passed_if_stream_body_retrieved()
    {
        $expect = 'Rougin\Slytherin\Http\Stream';

        // Verify the stream body is returned ----
        $actual = $this->self->getStream();

        $this->assertInstanceOf($expect, $actual);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $root = str_replace('Http', 'Fixture', __DIR__);

        $filepath = $root . '/Templates/file-test.php';

        file_put_contents($filepath, 'Hello world');

        $this->self = new UploadedFile($filepath, 400, 0, 'file-test.php', 'text/plain');
    }
}

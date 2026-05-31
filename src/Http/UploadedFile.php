<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\UploadedFileInterface;

Interop::register('UploadedFile');

/**
 * @property string       $file
 * @property integer|null $size
 * @property integer      $error
 * @property string|null  $name
 * @property string|null  $media
 *
 * @method string|null                       getClientFilename()
 * @method string|null                       getClientMediaType()
 * @method integer                           getError()
 * @method integer|null                      getSize()
 * @method \Psr\Http\Message\StreamInterface getStream()
 * @method void                              moveTo(string $targetPath)
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UploadedFile extends PsrUploadedFile implements UploadedFileInterface
{
    /**
     * Initializes the uploaded file instance.
     *
     * @param string       $file
     * @param integer|null $size
     * @param integer      $error
     * @param string|null  $name
     * @param string|null  $media
     */
    public function __construct($file, $size = null, $error = UPLOAD_ERR_OK, $name = null, $media = null)
    {
        $this->error = $error;

        $this->file = $file;

        $this->media = $media;

        $this->name = $name;

        $this->size = $size;
    }
}

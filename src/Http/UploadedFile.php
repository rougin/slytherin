<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rougin\Slytherin\Http;

/**
 * Uploaded File
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class UploadedFile implements \Psr\Http\Message\UploadedFileInterface
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var integer|null
     */
    protected $size;

    /**
     * @var integer|UPLOAD_ERR_OK
     */
    protected $error;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $media;

    /**
     * @param string  $file
     * @param integer $size
     * @param integer $error
     * @param string  $name
     * @param string  $media
     */
    public function __construct($file, $size = null, $error = UPLOAD_ERR_OK, $name = null, $media = null)
    {
        $this->error = $error;

        $this->file = $file;

        $this->media = $media;

        $this->name = $name;

        $this->size = $size;
    }

    /**
     * Retrieve a stream representing the uploaded file.
     *
     * @throws \RuntimeException
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getStream()
    {
        return new Stream(fopen($this->file, 'r'));
    }

    /**
     * Move the uploaded file to a new location.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @param string $targetPath
     */
    public function moveTo($targetPath)
    {
        rename($this->file, $targetPath);
    }

    /**
     * Retrieve the file size.
     *
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Retrieve the error associated with the uploaded file.
     *
     * @return integer
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retrieve the filename sent by the client.
     *
     * @return string|null
     */
    public function getClientFilename()
    {
        return $this->name;
    }

    /**
     * Retrieve the media type sent by the client.
     *
     * @return string|null
     */
    public function getClientMediaType()
    {
        return $this->media;
    }
}

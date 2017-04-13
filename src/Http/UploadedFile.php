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
    private $filePath;

    /**
     * @var integer|null
     */
    private $size;

    /**
     * @var integer|UPLOAD_ERR_OK
     */
    private $error;

    /**
     * @var string
     */
    private $clientFileName;

    /**
     * @var string
     */
    private $clientMediaType;

    /**
     * @param string  $filePath
     * @param integer $size
     * @param integer $error
     * @param string  $clientFileName
     * @param string  $clientMediaType
     */
    public function __construct($filePath, $size = null, $error = UPLOAD_ERR_OK, $clientFileName = null, $clientMediaType = null)
    {
        $this->clientFileName = $clientFileName;

        $this->clientMediaType = $clientMediaType;

        $this->error = $error;

        $this->filePath = $filePath;

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
        return new Stream(fopen($this->filePath, 'r'));
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
        rename($this->filePath, $targetPath);
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
        return $this->clientFileName;
    }

    /**
     * Retrieve the media type sent by the client.
     *
     * @return string|null
     */
    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }
}

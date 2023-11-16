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

use Psr\Http\Message\UploadedFileInterface;

/**
 * Uploaded File
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class UploadedFile implements UploadedFileInterface
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
     * @var integer
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

    /**
     * Retrieves the filename sent by the client.
     *
     * @return string|null
     */
    public function getClientFilename()
    {
        return $this->name;
    }

    /**
     * Retrieves the media type sent by the client.
     *
     * @return string|null
     */
    public function getClientMediaType()
    {
        return $this->media;
    }

    /**
     * Retrieves the error associated with the uploaded file.
     *
     * @return integer
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retrieves the file size.
     *
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Retrieves a stream representing the uploaded file.
     *
     * @return \Psr\Http\Message\StreamInterface
     *
     * @throws \RuntimeException
     */
    public function getStream()
    {
        // TODO: Add \RuntimeException

        $stream = fopen($this->file, 'r+');

        $stream = $stream === false ? null : $stream;

        return new Stream($stream);
    }

    /**
     * Move the uploaded file to a new location.
     *
     * @param string $target
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function moveTo($target)
    {
        // TODO: Add \InvalidArgumentException
        // TODO: Add \RuntimeException

        rename($this->file, $target);
    }

    /**
     * Parses the $_FILES into multiple \File instances.
     *
     * @param  array $uploaded
     * @param  array $files
     * @return \Psr\Http\Message\UploadedFileInterface[]
     */
    public static function normalize(array $uploaded, $files = array())
    {
        foreach (self::diverse($uploaded) as $name => $file) {
            list($files[$name], $items) = array($file, array());

            if (isset($file['name']) === true) {
                foreach ($file['name'] as $key => $value) {
                    $instance = self::create($file, $key);

                    $items[] = $instance;
                }

                $files[$name] = (array) $items;
            }
        }

        return $files;
    }

    /**
     * Creates a new UploadedFile instance.
     *
     * @param  array   $file
     * @param  integer $key
     * @return \Psr\Http\Message\UploadedFileInterface
     */
    protected static function create(array $file, $key)
    {
        $tmp = $file['tmp_name'][$key];

        $size = $file['size'][$key];

        $error = $file['error'][$key];

        $original = $file['name'][$key];

        $type = $file['type'][$key];

        return new UploadedFile($tmp, $size, $error, $original, $type);
    }

    /**
     * Diverse the $_FILES into a consistent result.
     *
     * @param  array $uploaded
     * @return array
     */
    protected static function diverse(array $uploaded)
    {
        $result = array();

        foreach ($uploaded as $file => $item) {
            foreach ($item as $key => $value) {
                $diversed = (array) $value;

                $result[$file][$key] = $diversed;
            }
        }

        return $result;
    }
}

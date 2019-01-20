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
     * Returns a \UploadedFile instance from $_FILES.
     *
     * @param  array $file
     * @return \Psr\Http\Message\UploadedFileInterface
     */
    public static function create(array $file)
    {
        if (is_array($file['tmp_name']) === false) {
            $tmp = (string) $file['tmp_name'];

            $size = $file['size'];

            $error = $file['error'];

            $original = $file['name'];

            $type = $file['type'];

            return new UploadedFile($tmp, $size, $error, $original, $type);
        }

        return self::nested($file);
    }

    /**
     * Returns an array of \UploadedFile instances from $_FILES.
     *
     * @param  array $files
     * @return \Psr\Http\Message\UploadedFileInterface[]
     */
    public static function nested(array $files)
    {
        $normalized = array();

        foreach (array_keys($files['tmp_name']) as $key) {
            $file = array('tmp_name' => $files['tmp_name'][$key]);

            $file['size'] = $files['size'][$key];

            $file['error'] = $files['error'][$key];

            $file['name'] = $files['name'][$key];

            $file['type'] = $files['type'][$key];

            $normalized[$key] = self::create($file);
        }

        return $normalized;
    }

    /**
     * Parses the $_FILES into multiple \UploadedFile instances.
     *
     * @param  array $files
     * @return \Psr\Http\Message\UploadedFileInterface[]
     */
    public static function normalize(array $files)
    {
        $normalized = array();

        foreach ((array) $files as $key => $value) {
            if ($value instanceof UploadedFileInterface) {
                $normalized[$key] = $value;
            } elseif (isset($value['tmp_name']) === true) {
                $normalized[$key] = self::create($value);
            } elseif (is_array($value) === true) {
                $normalized[$key] = self::normalize($value);
            }
        }

        return $normalized;
    }
}

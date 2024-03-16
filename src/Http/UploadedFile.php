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
     * @var string|null
     */
    protected $name = null;

    /**
     * @var string|null
     */
    protected $media = null;

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
     * @param  string $target
     * @return void
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
     * @param  array<string, array<string, string|string[]>> $uploaded
     * @return array<string, \Psr\Http\Message\UploadedFileInterface[]>
     */
    public static function normalize(array $uploaded)
    {
        $diversed = self::diverse($uploaded);

        /** @var array<string, \Psr\Http\Message\UploadedFileInterface[]> */
        $files = array();

        foreach ($diversed as $name => $file)
        {
            $items = array();

            if (! isset($file['name']))
            {
                continue;
            }

            foreach ($file['name'] as $key => $value)
            {
                $items[] = self::create($file, $key);
            }

            $files[$name] = (array) $items;
        }

        return (array) $files;
    }

    /**
     * Creates a new UploadedFile instance.
     *
     * @param  array<string, array<int, string|integer>> $file
     * @param  integer                                   $key
     * @return \Psr\Http\Message\UploadedFileInterface
     */
    protected static function create(array $file, $key)
    {
        /** @var string */
        $tmp = $file['tmp_name'][$key];

        /** @var integer */
        $size = $file['size'][$key];

        /** @var integer */
        $error = $file['error'][$key];

        /** @var string */
        $original = $file['name'][$key];

        /** @var string */
        $type = $file['type'][$key];

        return new UploadedFile($tmp, $size, $error, $original, $type);
    }

    /**
     * Diverse the $_FILES into a consistent result.
     *
     * @param  array<string, array<string, string|string[]>> $uploaded
     * @return array<string, array<string, string[]>>
     */
    protected static function diverse(array $uploaded)
    {
        $result = array();

        foreach ($uploaded as $file => $item)
        {
            foreach ($item as $key => $value)
            {
                $result[$file][$key] = (array) $value;
            }
        }

        return $result;
    }
}

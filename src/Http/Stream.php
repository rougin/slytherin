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

use Psr\Http\Message\StreamInterface;

/**
 * Stream
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Jérémy 'Jejem' Desvages <jejem@phyrexia.org>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Stream implements StreamInterface
{
    /**
     * Underline stream.
     *
     * @var resource|null
     */
    protected $stream = null;

    /**
     * Size of file.
     *
     * @var int|null
     */
    protected $size = null;

    /**
     * Metadata of file.
     *
     * @var array|null
     */
    protected $meta = null;

    /**
     * Resource modes.
     *
     * @var array
     */
    protected $modes = array();

    /**
     * Initializes the stream instance.
     *
     * @param resource|null $stream
     */
    public function __construct($stream = null)
    {
        $this->stream = $stream;

        $this->modes['readable'] = array('r', 'r+', 'w+', 'a+', 'x+', 'c+', 'w+b');

        $this->modes['writable'] = array('r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'w+b');
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $this->rewind();

            return $this->getContents();
        } catch (\RuntimeException $e) {
            return '';
        }
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close()
    {
        if ($this->stream !== null) {
            fclose($this->stream);
        }

        $this->detach();
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * @return resource|null
     */
    public function detach()
    {
        $stream = $this->stream;

        $this->stream = null;

        $this->size = null;

        $this->meta = null;

        return $stream;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return integer|null
     */
    public function getSize()
    {
        if ($this->stream !== null && $this->size === null) {
            $stats = fstat($this->stream);

            $this->size = isset($stats['size']) ? $stats['size'] : null;
        }

        return $this->size;
    }

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @return integer
     *
     * @throws \RuntimeException
     */
    public function tell()
    {
        if ($this->stream === null || ($position = ftell($this->stream)) === false) {
            $message = 'Could not get the position of the pointer in stream';

            throw new \RuntimeException($message);
        }

        return $position;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return boolean
     */
    public function eof()
    {
        return $this->stream !== null ? feof($this->stream) : true;
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return boolean
     */
    public function isSeekable()
    {
        return $this->getMetadata('seekable');
    }

    /**
     * Seek to a position in the stream.
     *
     * @param integer $offset
     * @param integer $whence
     *
     * @throws \RuntimeException
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (! $this->isSeekable() || fseek($this->stream, $offset, $whence) === -1) {
            $message = 'Could not seek in stream';

            throw new \RuntimeException($message);
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * @throws \RuntimeException
     */
    public function rewind()
    {
        $this->seek(0);
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return boolean
     */
    public function isWritable()
    {
        $fileMode = $this->getMetadata('mode');

        return (in_array($fileMode, $this->modes['writable']));
    }

    /**
     * Write data to the stream.
     *
     * @param  string $string
     * @return integer
     *
     * @throws \RuntimeException
     */
    public function write($string)
    {
        if (! $this->isWritable() || ($written = fwrite($this->stream, $string)) === false) {
            $message = 'Could not write to stream';

            throw new \RuntimeException($message);
        }

        $this->size = null;

        return $written;
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return boolean
     */
    public function isReadable()
    {
        $fileMode = $this->getMetadata('mode');

        return (in_array($fileMode, $this->modes['readable']));
    }

    /**
     * Read data from the stream.
     *
     * @param  integer $length
     * @return string
     *
     * @throws \RuntimeException
     */
    public function read($length)
    {
        if (! $this->isReadable() || ($data = fread($this->stream, $length)) === false) {
            $message = 'Could not read from stream';

            throw new \RuntimeException($message);
        }

        return $data;
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getContents()
    {
        if (! $this->isReadable() || ($contents = stream_get_contents($this->stream)) === false) {
            $message = 'Could not get contents of stream';

            throw new \RuntimeException($message);
        }

        return $contents;
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * @param  string $key
     * @return array|mixed|null
     */
    public function getMetadata($key = null)
    {
        if (isset($this->stream)) {
            $this->meta = stream_get_meta_data($this->stream);

            if (is_null($key) === true) {
                return $this->meta;
            }
        }

        return isset($this->meta[$key]) ? $this->meta[$key] : null;
    }
}

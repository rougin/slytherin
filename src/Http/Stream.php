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
 * Stream
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Jérémy 'Jejem' Desvages <jejem@phyrexia.org>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Stream implements \Psr\Http\Message\StreamInterface
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
     * @param resource|string $stream
     * @param string          $mode
     */
    public function __construct($stream, $mode = 'r')
    {
        $this->stream = is_string($stream) ? fopen($stream, $mode) : $stream;

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
        $oldStream = $this->stream;

        $this->stream = null;

        $this->size = null;

        $this->meta = null;

        return $oldStream;
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
     * @throws \RuntimeException
     *
     * @return integer
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
     * @throws \RuntimeException
     *
     * @param integer $offset
     * @param integer $whence
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        $this->isSeekable() || $this->exception('unseekable');

        $seek = fseek($this->stream, $offset, $whence);

        $seek !== -1 || $this->exception('unseekable');

        return $seek;
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
        $mode = $this->getMetadata('mode');

        return (in_array($mode, $this->modes['writable']));
    }

    /**
     * Write data to the stream.
     *
     * @throws \RuntimeException
     *
     * @param  string $string
     * @return integer
     */
    public function write($string)
    {
        $this->isWritable() || $this->exception('unwritable');

        $written = fwrite($this->stream, $string);

        $written !== false || $this->exception('unwritable');

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
        $mode = $this->getMetadata('mode');

        return (in_array($mode, $this->modes['readable']));
    }

    /**
     * Read data from the stream.
     *
     * @throws \RuntimeException
     *
     * @param  integer $length
     * @return string
     */
    public function read($length)
    {
        $this->isReadable() || $this->exception('unreadable');

        $data = fread($this->stream, $length);

        $data !== false || $this->exception('unreadable');

        return $data;
    }

    /**
     * Returns the remaining contents in a string
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function getContents()
    {
        $this->isReadable() || $this->exception('unreadable');

        $contents = stream_get_contents($this->stream);

        $contents !== false || $this->exception('unreadable');

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
        ! isset($this->stream) || $this->meta = stream_get_meta_data($this->stream);

        $value = isset($this->meta[$key]) ? $this->meta[$key] : null;

        return $key === null ? $this->meta : $value;
    }

    /**
     * Throws specific RuntimeException errors.
     *
     * @throws \RuntimeException
     *
     * @param  string $error
     * @return void
     */
    protected function exception($error)
    {
        if ($error === 'unreadable') {
            $message = 'Stream is not readable';
        }

        if ($error === 'unseekable') {
            $message = 'Could not seek in stream';
        }

        if ($error === 'unwritable') {
            $message = 'Stream is not writable';
        }

        throw new \RuntimeException($message);
    }
}

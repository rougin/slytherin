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
 * Describes a data stream.
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Jérémy 'Jejem' Desvages <jejem@phyrexia.org>
 */
class Stream implements \Psr\Http\Message\StreamInterface
{
    /**
     * Underline stream.
     *
     * @var resource|null
     */
    private $stream = null;

    /**
     * Size of file.
     *
     * @var int|null
     */
    private $size = null;

    /**
     * Metadata of file.
     *
     * @var array|null
     */
    private $meta = null;

    /**
     * Resource modes.
     *
     * @var  array
     * @link http://php.net/manual/function.fopen.php
     */
    private $modes = [
        'readable' => [ 'r', 'r+', 'w+', 'a+', 'x+', 'c+', 'w+b' ],
        'writable' => [ 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+', 'w+b' ],
    ];

    /**
     * @param resource|null $stream
     */
    public function __construct($stream = null)
    {
        $this->stream = $stream;
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
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
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        $oldStream = $this->stream;

        $this->stream = null;
        $this->size   = null;
        $this->meta   = null;

        return $oldStream;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
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
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws \\RuntimeException on error.
     */
    public function tell()
    {
        if ($this->stream === null || ($position = ftell($this->stream)) === false) {
            throw new \RuntimeException('Could not get the position of the pointer in stream');
        }

        return $position;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof()
    {
        return $this->stream !== null ? feof($this->stream) : true;
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable()
    {
        return $this->getMetadata('seekable');
    }

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->isSeekable() || fseek($this->stream, $offset, $whence) === -1) {
            throw new \RuntimeException('Could not seek in stream');
        }
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind()
    {
        $this->seek(0);
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable()
    {
        $fileMode = $this->getMetadata('mode');

        return (in_array($fileMode, $this->modes['writable']));
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string)
    {
        if (! $this->isWritable() || ($written = fwrite($this->stream, $string)) === false) {
            throw new \RuntimeException('Could not write to stream');
        }

        // clear size
        $this->size = null;

        return $written;
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable()
    {
        $fileMode = $this->getMetadata('mode');

        return (in_array($fileMode, $this->modes['readable']));
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length)
    {
        if (! $this->isReadable() || ($data = fread($this->stream, $length)) === false) {
            throw new \RuntimeException('Could not read from stream');
        }

        return $data;
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws \RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents()
    {
        if (! $this->isReadable() || ($contents = stream_get_contents($this->stream)) === false) {
            throw new \RuntimeException('Could not get contents of stream');
        }

        return $contents;
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
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

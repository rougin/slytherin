<?php

namespace Rougin\Slytherin\Http;

Interop::register('Stream');

/**
 * @package Slytherin
 *
 * @method string           __toString()
 * @method void             close()
 * @method resource|null    detach()
 * @method boolean          eof()
 * @method string           getContents()
 * @method array|mixed|null getMetadata(?string $key = null)
 * @method integer|null     getSize()
 * @method boolean          isReadable()
 * @method boolean          isSeekable()
 * @method boolean          isWritable()
 * @method string           read(integer $length)
 * @method void             rewind()
 * @method void             seek(integer $offset, integer $whence = 0)
 * @method integer          tell()
 * @method integer          write(string $string)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Stream extends PsrStream
{
    /**
     * Initializes the stream instance.
     *
     * @param resource|null $stream
     */
    public function __construct($stream = null)
    {
        $this->stream = $stream;
    }
}

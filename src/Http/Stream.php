<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\StreamInterface;

Interop::register('Stream');

/**
 * @property resource|null $stream
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
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Stream extends PsrStream implements StreamInterface
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

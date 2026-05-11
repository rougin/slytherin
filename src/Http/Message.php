<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\MessageInterface;

Interop::register('Message');

/**
 * @package Slytherin
 *
 * @method \Psr\Http\Message\StreamInterface  getBody()
 * @method string[]                           getHeader(string $name)
 * @method string                             getHeaderLine(string $name)
 * @method string[][]                         getHeaders()
 * @method string                             getProtocolVersion()
 * @method boolean                            hasHeader(string $name)
 * @method \Psr\Http\Message\MessageInterface withAddedHeader(string $name, $value)
 * @method \Psr\Http\Message\MessageInterface withBody(\Psr\Http\Message\StreamInterface $body)
 * @method \Psr\Http\Message\MessageInterface withHeader(string $name, $value)
 * @method \Psr\Http\Message\MessageInterface withoutHeader(string $name)
 * @method \Psr\Http\Message\MessageInterface withProtocolVersion(string $version)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Message extends PsrMessage implements MessageInterface
{
}

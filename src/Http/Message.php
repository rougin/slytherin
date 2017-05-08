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
 * Message
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Message implements \Psr\Http\Message\MessageInterface
{
    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    private $body;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var string
     */
    private $version = '1.1';

    /**
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array                                  $headers
     * @param string                                 $version
     */
    public function __construct(StreamInterface $body = null, array $headers = array(), $version = '1.1')
    {
        $this->body = ($body === null) ? new Stream(fopen('php://temp', 'r+')) : $body;

        $this->headers = $headers;

        $this->version = $version;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * @param  string $version
     * @return static
     */
    public function withProtocolVersion($version)
    {
        $new = clone $this;

        $new->version = $version;

        return $new;
    }

    /**
     * Retrieves all message header values.
     *
     * @return string[][]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * @param  string $name
     * @return string[]
     */
    public function hasHeader($name)
    {
        return isset($this->headers[$name]);
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * @param  string $name
     * @return string[]
     */
    public function getHeader($name)
    {
        return $this->hasHeader($name) ? $this->headers[$name] : array();
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * @param  string $name
     * @return string
     */
    public function getHeaderLine($name)
    {
        return $this->hasHeader($name) ? implode(',', $this->headers[$name]) : '';
    }

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string          $name
     * @param  string|string[] $value
     * @return static
     */
    public function withHeader($name, $value)
    {
        $new = clone $this;

        $new->headers[$name] = (is_array($value)) ? $value : array($value);

        return $new;
    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string          $name
     * @param  string|string[] $value
     * @return static
     */
    public function withAddedHeader($name, $value)
    {
        $new = clone $this;

        $new->headers[$name][] = $value;

        return $new;
    }

    /**
     * Return an instance without the specified header.
     *
     * @param  string $name
     * @return static
     */
    public function withoutHeader($name)
    {
        if ($this->hasHeader($name)) {
            $new = clone $this;

            unset($new->headers[$name]);

            return $new;
        }

        return clone $this;
    }

    /**
     * Gets the body of the message.
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return an instance with the specified message body.
     *
     * @throws \InvalidArgumentException
     *
     * @param  \Psr\Http\Message\StreamInterface $body
     * @return static
     */
    public function withBody(StreamInterface $body)
    {
        $new = clone $this;

        $new->body = $body;

        return $new;
    }
}

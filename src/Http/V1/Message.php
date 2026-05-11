<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rougin\Slytherin\Http\V1;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\MessageInterface;
use Rougin\Slytherin\Http\Stream;

/**
 * @package Slytherin
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Message implements MessageInterface
{
    /**
     * @var \Psr\Http\Message\StreamInterface
     */
    protected $body;

    /**
     * @var array<string, string[]>
     */
    protected $headers = array();

    /**
     * @var string
     */
    protected $version = '1.1';

    /**
     * Initializes the message instance.
     *
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array<string, string[]>                $headers
     * @param string                                 $version
     *
     * @todo Remove usage of "null" in this method.
     */
    public function __construct($body = null, array $headers = array(), $version = '1.1')
    {
        if ($body === null)
        {
            $resource = fopen('php://temp', 'r+');

            $resource = ! $resource ? null : $resource;

            $body = new Stream($resource);
        }

        $this->headers = $headers;

        $this->body = $body;

        $this->version = $version;
    }

    /**
     * Returns the body of the message.
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Returns the actual header key matching the given
     * case-insensitive name.
     *
     * @param string $name
     *
     * @return string|null
     */
    protected function getHeaderKey($name)
    {
        $name = strtolower($name);

        foreach (array_keys($this->headers) as $key)
        {
            if (strtolower($key) === $name)
            {
                return $key;
            }
        }

        return null;
    }

    /**
     * Retrieves a message header value by the given
     * case-insensitive name.
     *
     * @param string $name
     *
     * @return string[]
     */
    public function getHeader($name)
    {
        $key = $this->getHeaderKey($name);

        $value = array();

        if ($key !== null)
        {
            $value = $this->headers[$key];
        }

        return $value;
    }

    /**
     * Retrieves a comma-separated string of the values
     * for a single header.
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine($name)
    {
        $key = $this->getHeaderKey($name);

        $value = '';

        if ($key !== null)
        {
            $value = implode(',', $this->headers[$key]);
        }

        return $value;
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
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->version;
    }

    /**
     * Retrieves a message header value by the given
     * case-insensitive name.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasHeader($name)
    {
        return $this->getHeaderKey($name) !== null;
    }

    /**
     * Returns an instance with the specified header
     * appended with the given value.
     *
     * @param string          $name
     * @param string|string[] $value
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withAddedHeader($name, $value)
    {
        $this->checkIfValidName($name);

        $static = clone $this;

        if (! is_array($value))
        {
            $static->headers[$name][] = $value;

            return $static;
        }

        $items = $this->getHeader($name);

        $value = array_merge($items, $value);

        $static->headers[$name] = $value;

        return $static;
    }

    /**
     * Returns an instance with the specified
     * message body.
     *
     * @param \Psr\Http\Message\StreamInterface $body
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withBody(StreamInterface $body)
    {
        $static = clone $this;

        $static->body = $body;

        return $static;
    }

    /**
     * Returns an instance with the provided value
     * replacing the specified header.
     *
     * @param string          $name
     * @param string|string[] $value
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withHeader($name, $value)
    {
        $this->checkIfValidName($name);

        $static = clone $this;

        if (! is_array($value))
        {
            $value = array($value);
        }

        $static->headers[$name] = $value;

        return $static;
    }

    /**
     * Validates the specified header name.
     *
     * @param string $name
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkIfValidName($name)
    {
        $pattern = '/^[a-zA-Z0-9!#$%&\'*+.^_`|~-]+$/';

        if (preg_match($pattern, $name))
        {
            return;
        }

        $text = 'Header name is not a valid RFC 7230 name.';

        throw new \InvalidArgumentException($text);
    }

    /**
     * Returns an instance with the specified HTTP
     * protocol version.
     *
     * @param string $version
     *
     * @return static
     */
    public function withProtocolVersion($version)
    {
        $static = clone $this;

        $static->version = $version;

        return $static;
    }

    /**
     * Returns an instance without the specified header.
     *
     * @param string $name
     *
     * @return static
     */
    public function withoutHeader($name)
    {
        $static = clone $this;

        $key = $this->getHeaderKey($name);

        if ($key !== null)
        {
            unset($static->headers[$key]);
        }

        return $static;
    }
}

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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Server Request
 *
 * @package Slytherin
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * @var array<string, string>
     */
    protected $attributes = array();

    /**
     * @var array<string, string>
     */
    protected $cookies = array();

    /**
     * @var array<string, mixed>|object|null
     */
    protected $data;

    /**
     * @var array<string, string>
     */
    protected $query = array();

    /**
     * @var array<string, string>
     */
    protected $server = array();

    /**
     * @var array<string, \Psr\Http\Message\UploadedFileInterface[]>
     */
    protected $uploaded = array();

    /**
     * Initializes the server request instance.
     *
     * @param array<string, string>                         $server
     * @param array<string, string>                         $cookies
     * @param array<string, string>                         $query
     * @param array<string, array<string, string|string[]>> $uploaded
     * @param array<string, mixed>|object|null              $data
     * @param array<string, string>                         $attributes
     * @param \Psr\Http\Message\UriInterface|null           $uri
     * @param \Psr\Http\Message\StreamInterface|null        $body
     * @param array<string, string[]>                       $headers
     * @param string                                        $version
     */
    public function __construct(array $server, array $cookies = array(), array $query = array(), array $uploaded = array(), $data = null, array $attributes = array(), UriInterface $uri = null, StreamInterface $body = null, array $headers = array(), $version = '1.1')
    {
        $uri = $uri === null ? Uri::instance($server) : $uri;

        $method = $server['REQUEST_METHOD'];

        $target = $server['REQUEST_URI'];

        parent::__construct($method, $target, $uri, $body, $headers, $version);

        $this->cookies = $cookies;

        $this->data = $data;

        $this->query = $query;

        $this->server = $server;

        $this->uploaded = UploadedFile::normalize($uploaded);

        $this->attributes = $attributes;

        // NOTE: To be removed in v1.0.0. Attributes should be empty on default. -----
        if (! $attributes)
        {
            $this->attributes = array_merge($cookies, (array) $data, $query, $server);
        }
        // ---------------------------------------------------------------------------
    }

    /**
     * Retrieves a single derived request attribute.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * @return array<string, string>
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Retrieve cookies.
     *
     * @return array<string, string>
     */
    public function getCookieParams()
    {
        return $this->cookies;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return array<string, mixed>|object|null
     */
    public function getParsedBody()
    {
        return $this->data;
    }

    /**
     * Retrieve query string arguments.
     *
     * @return array<string, string>
     */
    public function getQueryParams()
    {
        return $this->query;
    }

    /**
     * Retrieve server parameters.
     *
     * @return array<string, string>
     */
    public function getServerParams()
    {
        return $this->server;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return array<string, \Psr\Http\Message\UploadedFileInterface[]>
     */
    public function getUploadedFiles()
    {
        return $this->uploaded;
    }

    /**
     * Returns an instance with the specified derived request attribute.
     *
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $static = clone $this;

        $static->attributes[$name] = $value;

        return $static;
    }

    /**
     * Returns an instance with the specified cookies.
     *
     * @param array<string, string> $cookies
     *
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $static = clone $this;

        $static->cookies = $cookies;

        return $static;
    }

    /**
     * Returns an instance with the specified body parameters.
     *
     * @param array<string, mixed>|object|null $data
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withParsedBody($data)
    {
        // TODO: Add \InvalidArgumentException

        $static = clone $this;

        $static->data = $data;

        return $static;
    }

    /**
     * Returns an instance with the specified query string arguments.
     *
     * @param array<string, string> $query
     *
     * @return static
     */
    public function withQueryParams(array $query)
    {
        $static = clone $this;

        $static->query = $query;

        return $static;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * @param array<string, \Psr\Http\Message\UploadedFileInterface[]> $uploaded
     *
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withUploadedFiles(array $uploaded)
    {
        // TODO: Add \InvalidArgumentException

        $static = clone $this;

        $static->uploaded = $uploaded;

        return $static;
    }

    /**
     * Returns an instance that removes the specified derived request attribute.
     *
     * @param string $name
     *
     * @return static
     */
    public function withoutAttribute($name)
    {
        $static = clone $this;

        unset($static->attributes[$name]);

        return $static;
    }
}

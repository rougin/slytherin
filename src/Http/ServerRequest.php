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
use Psr\Http\Message\UriInterface;

/**
 * Server Request
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ServerRequest extends Request implements \Psr\Http\Message\ServerRequestInterface
{
    /**
     * @var array
     */
    private $server;

    /**
     * @var array
     */
    private $cookies;

    /**
     * @var array
     */
    private $query;

    /**
     * @var array
     */
    private $uploadedFiles;

    /**
     * @var array|null|object
     */
    private $data;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param array                                  $server
     * @param array                                  $cookies
     * @param array                                  $query
     * @param array                                  $uploadedFiles
     * @param array|null                             $data
     * @param array                                  $attributes
     * @param \Psr\Http\Message\UriInterface|null    $uri
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array                                  $headers
     * @param string                                 $version
     */
    public function __construct(array $server = array(), array $cookies = array(), array $query = array(), array $uploadedFiles = array(), $data = null, array $attributes = array(), UriInterface $uri = null, StreamInterface $body = null, array $headers = array(), $version = '1.1')
    {
        $http = (! empty($server['HTTPS']) && $server['HTTPS'] != 'off') ? 'https' : 'http';

        $uri = ($uri === null) ? new Uri($http . '://' . $server['SERVER_NAME'] . ':' . $server['SERVER_PORT'] . $server['REQUEST_URI']) : $uri;

        parent::__construct($server['REQUEST_METHOD'], $server['REQUEST_URI'], $uri, $body, $headers, $version);

        $this->attributes = $attributes;

        $this->cookies = $cookies;

        $this->data = $data;

        $this->query = $query;

        $this->server = $server;

        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * Retrieve server parameters.
     *
     * @return array
     */
    public function getServerParams()
    {
        return $this->server;
    }

    /**
     * Retrieve cookies.
     *
     * @return array
     */
    public function getCookieParams()
    {
        return $this->cookies;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * @param  array $cookies
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $this->cookies = $cookies;

        return clone $this;
    }

    /**
     * Retrieve query string arguments.
     *
     * @return array
     */
    public function getQueryParams()
    {
        return $this->query;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * @param  array $query
     * @return static
     */
    public function withQueryParams(array $query)
    {
        $this->query = $query;

        return clone $this;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return array
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * @throws \InvalidArgumentException
     *
     * @param  array $uploadedFiles
     * @return static
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return clone $this;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return null|array|object
     */
    public function getParsedBody()
    {
        return $this->data;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * @throws \InvalidArgumentException
     *
     * @param  null|array|object $data
     * @return static
     */
    public function withParsedBody($data)
    {
        $this->data = $data;

        return clone $this;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * @param  string $name
     * @param  mixed  $default
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return clone $this;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * @param  string $name
     * @return static
     */
    public function withoutAttribute($name)
    {
        $new = clone $this;

        unset($new->attributes[$name]);

        return $new;
    }
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rougin\Slytherin\Http\V2;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

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
     * @param array<string, string>                                 $server
     * @param array<string, string>                                 $cookies
     * @param array<string, string>                                 $query
     * @param array<string, array<string, integer|string|string[]>> $uploaded
     * @param array<string, mixed>|object|null                      $data
     * @param array<string, string>                                 $attributes
     * @param \Psr\Http\Message\UriInterface|null                   $uri
     * @param \Psr\Http\Message\StreamInterface|null                $body
     * @param array<string, string[]>                               $headers
     * @param string                                                $version
     */
    public function __construct(array $server, array $cookies = array(), array $query = array(), array $uploaded = array(), $data = null, array $attributes = array(), $uri = null, $body = null, array $headers = array(), $version = '1.1')
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
    }

    /**
     * Retrieves a single derived request attribute.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getAttribute(string $name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Retrieve cookies.
     *
     * @return array<string, string>
     */
    public function getCookieParams(): array
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
    public function getQueryParams(): array
    {
        return $this->query;
    }

    /**
     * Retrieve server parameters.
     *
     * @return array<string, string>
     */
    public function getServerParams(): array
    {
        return $this->server;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return array<string, \Psr\Http\Message\UploadedFileInterface[]>
     */
    public function getUploadedFiles(): array
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
    public function withAttribute(string $name, $value): ServerRequestInterface
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
    public function withCookieParams(array $cookies): ServerRequestInterface
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
     * @throws \InvalidArgumentException
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        if (! $this->isValidParsedBody($data))
        {
            $text = 'Parsed body must be null, an array, or an object.';

            throw new \InvalidArgumentException($text);
        }

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
    public function withQueryParams(array $query): ServerRequestInterface
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
     * @throws \InvalidArgumentException
     */
    public function withUploadedFiles(array $uploaded): ServerRequestInterface
    {
        foreach ($uploaded as $items)
        {
            foreach ($items as $file)
            {
                $this->checkIfValidFile($file);
            }
        }

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
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $static = clone $this;

        unset($static->attributes[$name]);

        return $static;
    }

    /**
     * Validates the specified uploaded file.
     *
     * @param mixed $file
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkIfValidFile($file)
    {
        if ($file instanceof UploadedFileInterface)
        {
            return;
        }

        $name = 'UploadedFileInterface';

        $text = 'Each file must be implemented in "' . $name . '".';

        throw new \InvalidArgumentException($text);
    }

    /**
     * Checks if the specified data is a valid parsed body type.
     *
     * @param mixed $data
     *
     * @return boolean
     */
    protected function isValidParsedBody($data)
    {
        return is_null($data) || is_array($data) || is_object($data);
    }
}

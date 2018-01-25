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
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * @var array
     */
    protected $server = array();

    /**
     * @var array
     */
    protected $cookies = array();

    /**
     * @var array
     */
    protected $query = array();

    /**
     * @var array
     */
    protected $uploaded = array();

    /**
     * @var array|null|object
     */
    protected $data;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * Initializes the server request instance.
     *
     * @param array                                  $server
     * @param array                                  $cookies
     * @param array                                  $query
     * @param array                                  $uploaded
     * @param array|null                             $data
     * @param array                                  $attributes
     * @param \Psr\Http\Message\UriInterface|null    $uri
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array                                  $headers
     * @param string                                 $version
     */
    public function __construct(array $server = array(), array $cookies = array(), array $query = array(), array $uploaded = array(), $data = null, array $attributes = array(), UriInterface $uri = null, StreamInterface $body = null, array $headers = array(), $version = '1.1')
    {
        parent::__construct($server['REQUEST_METHOD'], $server['REQUEST_URI'], $this->uri($server, $uri), $body, $headers, $version);

        $this->attributes = array_merge($server, $cookies, $query, is_null($data) ? array() : $data);

        $this->cookies = $cookies;

        $this->data = $data;

        $this->query = $query;

        $this->server = $server;

        $this->uploaded = $this->uploaded($uploaded);
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
        $new = clone $this;

        $new->cookies = $cookies;

        return $new;
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
        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return \Psr\Http\Message\UploadedFileInterface[]
     */
    public function getUploadedFiles()
    {
        return $this->uploaded;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * @param  array $uploaded
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withUploadedFiles(array $uploaded)
    {
        // TODO: Add InvalidArgumentException

        $new = clone $this;

        $new->uploaded = $uploaded;

        return $new;
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
     * @param  null|array|object $data
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withParsedBody($data)
    {
        // TODO: Add InvalidArgumentException

        $new = clone $this;

        $new->data = $data;

        return $new;
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
        $new = clone $this;

        $new->attributes[$name] = $value;

        return $new;
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

    /**
     * Updates the contents into array for single uploaded file.
     *
     * @param  array $file
     * @return array
     */
    public function arrayify(array $file)
    {
        if (is_array($file['name']) === false) {
            $file['tmp_name'] = array($file['tmp_name']);

            $file['size'] = array($file['size']);

            $file['error'] = array($file['error']);

            $file['name'] = array($file['name']);

            $file['type'] = array($file['type']);
        }

        return $file;
    }

    /**
     * Creates a new \Psr\Http\Message\UploadedFile instance.
     *
     * @param  array  $file
     * @return \Psr\Http\Message\UploadedFile
     */
    protected function file(array $file)
    {
        $tmp = $file['tmp_name'];

        $size = $file['size'];

        $error = $file['error'];

        $original = $file['name'];

        $type = $file['type'];

        return new UploadedFile($tmp, $size, $error, $original, $type);
    }

    /**
     * Parses the $_FILES into multiple \Psr\Http\Message\UploadedFile instances.
     *
     * @param  array $uploaded
     * @return \Psr\Http\Message\UploadedFile[]
     */
    protected function uploaded(array $uploaded)
    {
        $files = array();

        foreach ($uploaded as $name => $file) {
            $file = $this->arrayify($file);

            list($count, $files[$name]) = array(count($file['name']), array());

            for ($i = 0; $i < $count; $i++) {
                foreach (array_keys($file) as $key) {
                    $files[$name][$i][$key] = $file[$key][$i];
                }

                $files[$name][$i] = $this->file($files[$name][$i]);
            }
        }

        return $files;
    }

    /**
     * Generates a \Psr\Http\Message\UriInterface if it does not exists.
     *
     * @param  array                               $server
     * @param  \Psr\Http\Message\UriInterface|null $uri
     * @return \Psr\Http\Message\UriInterface
     */
    protected function uri(array $server, $uri = null)
    {
        $http = (! empty($server['HTTPS']) && $server['HTTPS'] != 'off') ? 'https' : 'http';

        $url = $http . '://' . $server['SERVER_NAME'] . ':' . $server['SERVER_PORT'] . $server['REQUEST_URI'];

        return ($uri === null) ? new Uri($url) : $uri;
    }
}

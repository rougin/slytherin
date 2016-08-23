<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rougin\Slytherin\Test\Fixture\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ServerRequest extends Message implements ServerRequestInterface
{
    private $requestTarget;
    private $method;
    private $uri;
    private $server;
    private $cookies;
    private $query;
    private $uploadedFiles;
    private $data;
    private $attributes;

    public function __construct($version = '1.1', array $headers = array(), StreamInterface $body = null, $requestTarget = '/', $method = 'GET', UriInterface $uri = null, array $server = array(), array $cookies = array(), array $query = array(), array $uploadedFiles = array(), $data = null, array $attributes = array())
    {
        parent::__construct($version, $headers, $body);

        $this->requestTarget = $requestTarget;
        $this->method = $method;
        $this->uri = $uri;
        $this->server = $server;
        $this->cookies = $cookies;
        $this->query = $query;
        $this->uploadedFiles = $uploadedFiles;
        $this->data = $data;
        $this->attributes = $attributes;
    }

    public function getRequestTarget()
    {
        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget)
    {
        throw new \BadMethodCallException('Not implemented.');
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;

        return $this;
    }

    public function getServerParams()
    {
        return $this->server;
    }

    public function getCookieParams()
    {
        return $this->cookies;
    }

    public function withCookieParams(array $cookies)
    {
        throw new \BadMethodCallException('Not implemented.');
    }

    public function getQueryParams()
    {
        return $this->query;
    }

    public function withQueryParams(array $query)
    {
        $this->query = $query;

        return $this;
    }

    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        throw new \BadMethodCallException('Not implemented.');
    }

    public function getParsedBody()
    {
        return $this->data;
    }

    public function withParsedBody($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function withoutAttribute($name)
    {
        throw new \BadMethodCallException('Not implemented.');
    }
}

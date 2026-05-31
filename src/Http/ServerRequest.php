<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\ServerRequestInterface;

Interop::register('ServerRequest');

/**
 * @package Slytherin
 *
 * @method mixed                                                    getAttribute(string $name, $default = null)
 * @method array<string, string>                                    getAttributes()
 * @method array<string, string>                                    getCookieParams()
 * @method array<string, mixed>|object|null                         getParsedBody()
 * @method array<string, string>                                    getQueryParams()
 * @method array<string, string>                                    getServerParams()
 * @method array<string, \Psr\Http\Message\UploadedFileInterface[]> getUploadedFiles()
 * @method \Psr\Http\Message\ServerRequestInterface                 withAttribute(string $name, $value)
 * @method \Psr\Http\Message\ServerRequestInterface                 withCookieParams(array<string, string> $cookies)
 * @method \Psr\Http\Message\ServerRequestInterface                 withoutAttribute(string $name)
 * @method \Psr\Http\Message\ServerRequestInterface                 withParsedBody($data)
 * @method \Psr\Http\Message\ServerRequestInterface                 withQueryParams(array<string, string> $query)
 * @method \Psr\Http\Message\ServerRequestInterface                 withUploadedFiles(array<string, \Psr\Http\Message\UploadedFileInterface[]> $uploadedFiles)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ServerRequest extends PsrServerRequest implements ServerRequestInterface
{
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

        parent::__construct($body, $headers, $version);

        $this->method = $method;

        $this->target = $target;

        $this->uri = $uri;

        $this->cookies = $cookies;

        $this->data = $data;

        $this->query = $query;

        $this->server = $server;

        $this->uploaded = UploadedFile::normalize($uploaded);

        $this->attributes = $attributes;
    }
}

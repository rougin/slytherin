<?php

namespace Rougin\Slytherin\Http;

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
class ServerRequest extends PsrServerRequest
{
}

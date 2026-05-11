<?php

namespace Rougin\Slytherin\Http;

Interop::register('Request');

/**
 * @package Slytherin
 *
 * @method string                             getMethod()
 * @method string                             getRequestTarget()
 * @method \Psr\Http\Message\UriInterface     getUri()
 * @method \Psr\Http\Message\RequestInterface withMethod(string $method)
 * @method \Psr\Http\Message\RequestInterface withRequestTarget(string $requestTarget)
 * @method \Psr\Http\Message\RequestInterface withUri(\Psr\Http\Message\UriInterface $uri, bool $preserveHost = false)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Request extends PsrRequest
{
}

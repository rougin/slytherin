<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\RequestInterface;

Interop::register('Request');

/**
 * @property string                         $method
 * @property string                         $target
 * @property \Psr\Http\Message\UriInterface $uri
 *
 * @method string                             getMethod()
 * @method string                             getRequestTarget()
 * @method \Psr\Http\Message\UriInterface     getUri()
 * @method \Psr\Http\Message\RequestInterface withMethod(string $method)
 * @method \Psr\Http\Message\RequestInterface withRequestTarget(string $requestTarget)
 * @method \Psr\Http\Message\RequestInterface withUri(\Psr\Http\Message\UriInterface $uri, bool $preserveHost = false)
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Request extends PsrRequest implements RequestInterface
{
    /**
     * Initializes the request instance.
     *
     * @param string                                 $method
     * @param string                                 $target
     * @param \Psr\Http\Message\UriInterface|null    $uri
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array<string, string[]>                $headers
     * @param string                                 $version
     *
     * @todo Remove usage of "null" in this method.
     */
    public function __construct($method = 'GET', $target = '/', $uri = null, $body = null, array $headers = array(), $version = '1.1')
    {
        parent::__construct($body, $headers, $version);

        $this->method = $method;

        $this->target = $target;

        $this->uri = $uri === null ? new Uri : $uri;
    }
}

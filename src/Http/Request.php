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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Request
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Request extends Message implements RequestInterface
{
    /**
     * @var string
     */
    protected $target = '/';

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;

    /**
     * Initializes the request instance.
     *
     * @param string                                 $method
     * @param string                                 $target
     * @param \Psr\Http\Message\UriInterface|null    $uri
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array<string, string[]>                $headers
     * @param string                                 $version
     */
    public function __construct($method = 'GET', $target = '/', UriInterface $uri = null, StreamInterface $body = null, array $headers = array(), $version = '1.1')
    {
        parent::__construct($body, $headers, $version);

        $this->method = $method;

        $this->target = $target;

        $this->uri = $uri === null ? new Uri : $uri;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Retrieves the message's request target.
     *
     * @return string
     */
    public function getRequestTarget()
    {
        return $this->target;
    }

    /**
     * Retrieves the URI instance.
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided HTTP method.
     *
     * @param  string $method
     * @return static
     *
     * @throws \InvalidArgumentException
     */
    public function withMethod($method)
    {
        // TODO: Add \InvalidArgumentException

        $static = clone $this;

        $static->method = $method;

        return $static;
    }

    /**
     * Returns an instance with the specific request-target.
     *
     * @param  string $target
     * @return static
     */
    public function withRequestTarget($target)
    {
        $static = clone $this;

        $static->target = $target;

        return $static;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * @param  \Psr\Http\Message\UriInterface $uri
     * @param  boolean                        $preserve
     * @return static
     */
    public function withUri(UriInterface $uri, $preserve = false)
    {
        $static = clone $this;

        $static->uri = $uri;

        if (! $preserve && $host = $uri->getHost())
        {
            $port = $host . ':' . $uri->getPort();

            $host = $uri->getPort() ? $port : $host;

            $static->headers['Host'] = (array) $host;
        }

        return $static;
    }
}

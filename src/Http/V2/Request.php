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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Rougin\Slytherin\Http\Uri as HttpUri;

/**
 * Request
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
     *
     * @todo Remove usage of "null" in this method.
     */
    public function __construct($method = 'GET', $target = '/', $uri = null, $body = null, array $headers = array(), $version = '1.1')
    {
        parent::__construct($body, $headers, $version);

        $this->method = $method;

        $this->target = $target;

        $this->uri = $uri === null ? new HttpUri : $uri;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Retrieves the message's request target.
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        return $this->target;
    }

    /**
     * Retrieves the URI instance.
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided HTTP method.
     *
     * @param string $method
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withMethod(string $method): RequestInterface
    {
        if (empty($method))
        {
            $text = 'Method must be a non-empty string.';

            throw new \InvalidArgumentException($text);
        }

        $static = clone $this;

        $static->method = $method;

        return $static;
    }

    /**
     * Returns an instance with the specific request-target.
     *
     * @param string $requestTarget
     *
     * @return static
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $static = clone $this;

        $static->target = $requestTarget;

        return $static;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * @param \Psr\Http\Message\UriInterface $uri
     * @param boolean                        $preserveHost
     *
     * @return static
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $static = clone $this;

        $static->uri = $uri;

        $host = $uri->getHost();

        if ($preserveHost || $host === '')
        {
            return $static;
        }

        $port = $host . ':' . $uri->getPort();

        $host = $uri->getPort() ? $port : $host;

        $static->headers['Host'] = array($host);

        return $static;
    }
}

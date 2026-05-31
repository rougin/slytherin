<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rougin\Slytherin\Http\V1;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Rougin\Slytherin\Http\Uri as HttpUri;

/**
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
     * @param string $method
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withMethod($method)
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
     * Returns an instance with the specific
     * request-target.
     *
     * @param string $target
     *
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
     * @param \Psr\Http\Message\UriInterface $uri
     * @param boolean                        $preserve
     *
     * @return static
     */
    public function withUri(UriInterface $uri, $preserve = false)
    {
        $static = clone $this;

        $static->uri = $uri;

        $host = $uri->getHost();

        if ($preserve || $host === '')
        {
            return $static;
        }

        $port = $host . ':' . $uri->getPort();

        $host = $uri->getPort() ? $port : $host;

        $static->headers['Host'] = array($host);

        return $static;
    }
}

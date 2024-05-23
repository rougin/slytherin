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

use Psr\Http\Message\UriInterface;

/**
 * URI
 *
 * @package Slytherin
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Uri implements UriInterface
{
    /**
     * @var string
     */
    protected $fragment = '';

    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var string
     */
    protected $pass = '';

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var integer|null
     */
    protected $port = null;

    /**
     * @var string
     */
    protected $query = '';

    /**
     * @var string
     */
    protected $scheme = '';

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var string
     */
    protected $user = '';

    /**
     * Initializes the URI instance.
     *
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        $parts = parse_url($uri) ?: array();

        foreach ($parts as $key => $value)
        {
            $key === 'user' && $this->user = $value;

            $this->$key = $value;
        }

        $this->uri = (string) $uri;
    }

    /**
     * Return the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->uri;
    }

    /**
     * Retrieves the authority component of the URI.
     *
     * @return string
     */
    public function getAuthority()
    {
        $authority = $this->host;

        if ($this->host !== '' && $this->user !== null)
        {
            $authority = $this->user . '@' . $authority;

            $authority = $authority . ':' . $this->port;
        }

        return $authority;
    }

    /**
     * Retrieves the fragment component of the URI.
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Retrieves the host component of the URI.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Retrieves the path component of the URI.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Retrieves the port component of the URI.
     *
     * @return integer|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Retrieves the query string of the URI.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Retrieves the scheme component of the URI.
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Retrieves the user information component of the URI.
     *
     * @return string
     */
    public function getUserInfo()
    {
        return $this->user;
    }

    /**
     * Returns an instance with the specified URI fragment.
     *
     * @param string $fragment
     *
     * @return static
     */
    public function withFragment($fragment)
    {
        $new = clone $this;

        $new->fragment = $fragment;

        return $new;
    }

    /**
     * Returns an instance with the specified host.
     *
     * @param string $host
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withHost($host)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->host = $host;

        return $new;
    }

    /**
     * Returns an instance with the specified path.
     *
     * @param string $path
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withPath($path)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * Returns an instance with the specified port.
     *
     * @param integer|null $port
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withPort($port)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->port = $port;

        return $new;
    }

    /**
     * Returns an instance with the specified query string.
     *
     * @param string $query
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withQuery($query)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->query = $query;

        return $new;
    }

    /**
     * Returns an instance with the specified scheme.
     *
     * @param string $scheme
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withScheme($scheme)
    {
        // TODO: Add \InvalidArgumentException

        $new = clone $this;

        $new->scheme = $scheme;

        return $new;
    }

    /**
     * Returns an instance with the specified user information.
     *
     * @param string      $user
     * @param string|null $password
     *
     * @return static
     */
    public function withUserInfo($user, $password = null)
    {
        $new = clone $this;

        $new->user = $user . ':' . $password;

        return $new;
    }

    /**
     * Generates a \Psr\Http\Message\UriInterface from server variables.
     *
     * @param array<string, string> $server
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public static function instance(array $server)
    {
        $secure = isset($server['HTTPS']) ? 'on' : 'off';

        $http = $secure === 'off' ? 'http' : 'https';

        $url = $http . '://' . $server['SERVER_NAME'];

        $url .= (string) $server['SERVER_PORT'];

        return new Uri($url . $server['REQUEST_URI']);
    }
}

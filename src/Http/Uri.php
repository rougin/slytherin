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

/**
 * URI
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Uri implements \Psr\Http\Message\UriInterface
{
    /**
     * @var string
     */
    private $scheme = '';

    /**
     * @var string
     */
    private $userInfo = '';

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var integer|null
     */
    private $port = null;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $query = '';

    /**
     * @var string
     */
    private $fragment = '';

    /**
     * @var string
     */
    private $uriString = '';

    /**
     * @param string $uri
     */
    public function __construct($uri = '')
    {
        $parts = parse_url($uri);

        if (is_array($parts)) {
            foreach ($parts as $key => $value) {
                if ($key == 'user') {
                    $this->userInfo = $value;
                }

                $this->$key = $value;
            }
        }

        $this->uriString = $uri;
    }

    /**
     * Retrieve the scheme component of the URI.
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Retrieve the authority component of the URI.
     *
     * @return string
     */
    public function getAuthority()
    {
        $authority = $this->host;

        if (! empty($this->host) && ! empty($this->userInfo)) {
            $authority = $this->userInfo . '@' . $authority;

            $authority .= ':'. $this->port;
        }

        return $authority;
    }

    /**
     * Retrieve the user information component of the URI.
     *
     * @return string
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * Retrieve the host component of the URI.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Retrieve the port component of the URI.
     *
     * @return null|integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Retrieve the path component of the URI.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Retrieve the query string of the URI.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Retrieve the fragment component of the URI.
     *
     * @return string
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Return an instance with the specified scheme.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string $scheme
     * @return static
     */
    public function withScheme($scheme)
    {
        $this->scheme = $scheme;

        return clone $this;
    }

    /**
     * Return an instance with the specified user information.
     *
     * @param  string      $user
     * @param  null|string $password
     * @return static
     */
    public function withUserInfo($user, $password = null)
    {
        $this->userInfo = $user . '[:' . $password . ']';

        return clone $this;
    }

    /**
     * Return an instance with the specified host.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string $host
     * @return static
     */
    public function withHost($host)
    {
        $this->host = $host;

        return clone $this;
    }

    /**
     * Return an instance with the specified port.
     *
     * @throws \InvalidArgumentException
     *
     * @param  null|integer $port
     * @return static
     */
    public function withPort($port)
    {
        $this->port = $port;

        return clone $this;
    }

    /**
     * Return an instance with the specified path.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string $path
     * @return static
     */
    public function withPath($path)
    {
        $this->path = $path;

        return clone $this;
    }

    /**
     * Return an instance with the specified query string.
     *
     * @throws \InvalidArgumentException
     *
     * @param  string $query
     * @return static
     */
    public function withQuery($query)
    {
        $this->query = $query;

        return clone $this;
    }

    /**
     * Return an instance with the specified URI fragment.
     *
     * @param  string $fragment
     * @return static
     */
    public function withFragment($fragment)
    {
        $this->fragment = $fragment;

        return clone $this;
    }

    /**
     * Return the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->uriString;
    }
}

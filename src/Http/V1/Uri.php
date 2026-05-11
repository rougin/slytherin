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

use Psr\Http\Message\UriInterface;
use Rougin\Slytherin\Http\Uri as HttpUri;

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

        $url .= $server['SERVER_PORT'];

        return new HttpUri($url . $server['REQUEST_URI']);
    }

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

        $this->uri = $uri;
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
        $this->checkIfValidHost($host);

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
        $this->checkIfValidPath($path);

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
        if (! is_null($port) && ($port < 1 || $port > 65535))
        {
            $text = 'Port must be null or an integer between 1 and 65535.';

            throw new \InvalidArgumentException($text);
        }

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
        $this->checkIfValidQuery($query);

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
        $regex = '/^[a-zA-Z][a-zA-Z0-9+.-]*$/';

        if ($scheme !== '' && ! preg_match($regex, $scheme))
        {
            $text = 'Scheme must be empty or start with a letter.';

            throw new \InvalidArgumentException($text);
        }

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
     * Validates the specified host.
     *
     * @param mixed $host
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkIfValidHost($host)
    {
        if (is_string($host))
        {
            return;
        }

        $text = 'Host must be a valid string.';

        throw new \InvalidArgumentException($text);
    }

    /**
     * Validates the specified path.
     *
     * @param mixed $path
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkIfValidPath($path)
    {
        if (is_string($path))
        {
            return;
        }

        $text = 'Path must be a valid string.';

        throw new \InvalidArgumentException($text);
    }

    /**
     * Validates the specified query string.
     *
     * @param mixed $query
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkIfValidQuery($query)
    {
        if (is_string($query))
        {
            return;
        }

        $text = 'Query must be a valid string.';

        throw new \InvalidArgumentException($text);
    }
}

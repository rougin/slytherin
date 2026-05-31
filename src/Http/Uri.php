<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\UriInterface;

Interop::register('Uri');

/**
 * @property string       $fragment
 * @property string       $host
 * @property string       $pass
 * @property string       $path
 * @property integer|null $port
 * @property string       $query
 * @property string       $scheme
 * @property string       $uri
 * @property string       $user
 *
 * @method string                         __toString()
 * @method string                         getAuthority()
 * @method string                         getFragment()
 * @method string                         getHost()
 * @method string                         getPath()
 * @method integer|null                   getPort()
 * @method string                         getQuery()
 * @method string                         getScheme()
 * @method string                         getUserInfo()
 * @method \Psr\Http\Message\UriInterface withFragment(string $fragment)
 * @method \Psr\Http\Message\UriInterface withHost(string $host)
 * @method \Psr\Http\Message\UriInterface withPath(string $path)
 * @method \Psr\Http\Message\UriInterface withPort(?integer $port)
 * @method \Psr\Http\Message\UriInterface withQuery(string $query)
 * @method \Psr\Http\Message\UriInterface withScheme(string $scheme)
 * @method \Psr\Http\Message\UriInterface withUserInfo(string $user, ?string $password = null)
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Uri extends PsrUri implements UriInterface
{
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
            if ($key === 'user')
            {
                $this->user = $value;

                continue;
            }

            if ($key === 'host')
            {
                $this->host = strtolower($value);

                continue;
            }

            if ($key === 'path')
            {
                /** @var string */
                $value = preg_replace('#^/+#', '/', $value);

                $this->path = $value;

                continue;
            }

            $this->$key = $value;
        }
    }
}

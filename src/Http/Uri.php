<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\UriInterface;

Interop::register('Uri');

/**
 * @package Slytherin
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
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Uri extends PsrUri implements UriInterface
{
}

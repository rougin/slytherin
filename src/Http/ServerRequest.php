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

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * ServerRequest
 *
 * Representation of an incoming, server-side HTTP request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * Additionally, it encapsulates all data as it has arrived to the
 * application from the CGI and/or PHP environment, including:
 *
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body parameters (generally from $_POST)
 *
 * $_SERVER values MUST be treated as immutable, as they represent application
 * state at the time of request; as such, no methods are provided to allow
 * modification of those values. The other values provide such methods, as they
 * can be restored from $_SERVER or the request body, and may need treatment
 * during the application (e.g., body parameters may be deserialized based on
 * content type).
 *
 * Additionally, this interface recognizes the utility of introspecting a
 * request to derive and match additional parameters (e.g., via URI path
 * matching, decrypting cookie values, deserializing non-form-encoded body
 * content, matching authorization headers to users, etc). These parameters
 * are stored in an "attributes" property.
 *
 * Requests are considered immutable; all methods that might change state MUST
 * be implemented such that they retain the internal state of the current
 * message and return an instance that contains the changed state.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ServerRequest extends Request implements \Psr\Http\Message\ServerRequestInterface
{
    /**
     * @var array
     */
    private $server;

    /**
     * @var array
     */
    private $cookies;

    /**
     * @var array
     */
    private $query;

    /**
     * @var array
     */
    private $uploadedFiles;

    /**
     * @var array|null|object
     */
    private $data;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param array                                  $server
     * @param array                                  $cookies
     * @param array                                  $query
     * @param array                                  $uploadedFiles
     * @param array|null                             $data
     * @param array                                  $attributes
     * @param \Psr\Http\Message\UriInterface|null    $uri
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array                                  $headers
     * @param string                                 $version
     */
    public function __construct(array $server = array(), array $cookies = array(), array $query = array(), array $uploadedFiles = array(), $data = null, array $attributes = array(), UriInterface $uri = null, StreamInterface $body = null, array $headers = array(), $version = '1.1')
    {
        $http = (! empty($server['HTTPS']) && $server['HTTPS'] != 'off') ? 'https' : 'http';
        $uri  = ($uri === null) ? new Uri($http . '://' . $server['SERVER_NAME'] . ':' . $server['SERVER_PORT'] . $server['REQUEST_URI']) : $uri;

        parent::__construct($server['REQUEST_METHOD'], $server['REQUEST_URI'], $uri, $body, $headers, $version);

        $this->server = $server;
        $this->cookies = $cookies;
        $this->query = $query;
        $this->uploadedFiles = $uploadedFiles;
        $this->data = $data;
        $this->attributes = $attributes;
    }

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal. The data IS NOT
     * REQUIRED to originate from $_SERVER.
     *
     * @return array
     */
    public function getServerParams()
    {
        return $this->server;
    }

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * The data MUST be compatible with the structure of the $_COOKIE
     * superglobal.
     *
     * @return array
     */
    public function getCookieParams()
    {
        return $this->cookies;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * The data IS NOT REQUIRED to come from the $_COOKIE superglobal, but MUST
     * be compatible with the structure of $_COOKIE. Typically, this data will
     * be injected at instantiation.
     *
     * This method MUST NOT update the related Cookie header of the request
     * instance, nor related values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated cookie values.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     * @return static
     */
    public function withCookieParams(array $cookies)
    {
        $this->cookies = $cookies;

        return clone $this;
    }

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * Note: the query params might not be in sync with the URI or server
     * params. If you need to ensure you are only getting the original
     * values, you may need to parse the query string from `getUri()->getQuery()`
     * or from the `QUERY_STRING` server param.
     *
     * @return array
     */
    public function getQueryParams()
    {
        return $this->query;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * These values SHOULD remain immutable over the course of the incoming
     * request. They MAY be injected during instantiation, such as from PHP's
     * $_GET superglobal, or MAY be derived from some other value such as the
     * URI. In cases where the arguments are parsed from the URI, the data
     * MUST be compatible with what PHP's parse_str() would return for
     * purposes of how duplicate query parameters are handled, and how nested
     * sets are handled.
     *
     * Setting query string arguments MUST NOT change the URI stored by the
     * request, nor the values in the server params.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *     $_GET.
     * @return static
     */
    public function withQueryParams(array $query)
    {
        $this->query = $query;

        return clone $this;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * This method returns upload metadata in a normalized tree, with each leaf
     * an instance of Psr\Http\Message\UploadedFileInterface.
     *
     * These values MAY be prepared from $_FILES or the message body during
     * instantiation, or MAY be injected via withUploadedFiles().
     *
     * @return array An array tree of UploadedFileInterface instances; an empty
     *     array MUST be returned if no data is present.
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param array $uploadedFiles An array tree of UploadedFileInterface instances.
     * @return static
     * @throws \InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;

        return clone $this;
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, this method MUST
     * return the contents of $_POST.
     *
     * Otherwise, this method may return any results of deserializing
     * the request body content; as parsing returns structured content, the
     * potential types MUST be arrays or objects only. A null value indicates
     * the absence of body content.
     *
     * @return null|array|object The deserialized body parameters, if any.
     *     These will typically be an array or object.
     */
    public function getParsedBody()
    {
        return $this->data;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * These MAY be injected during instantiation.
     *
     * If the request Content-Type is either application/x-www-form-urlencoded
     * or multipart/form-data, and the request method is POST, use this method
     * ONLY to inject the contents of $_POST.
     *
     * The data IS NOT REQUIRED to come from $_POST, but MUST be the results of
     * deserializing the request body content. Deserialization/parsing returns
     * structured data, and, as such, this method ONLY accepts arrays or objects,
     * or a null value if nothing was available to parse.
     *
     * As an example, if content negotiation determines that the request data
     * is a JSON payload, this method could be used to create a request
     * instance with the deserialized parameters.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated body parameters.
     *
     * @param null|array|object $data The deserialized body data. This will
     *     typically be in an array or object.
     * @return static
     * @throws \InvalidArgumentException if an unsupported argument type is
     *     provided.
     */
    public function withParsedBody($data)
    {
        $this->data = $data;

        return clone $this;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application and request specific, and CAN be mutable.
     *
     * @return array Attributes derived from the request.
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * This method obviates the need for a hasAttribute() method, as it allows
     * specifying a default value to return if the attribute is not found.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $value The value of the attribute.
     * @return static
     */
    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return clone $this;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the attribute.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @return static
     */
    public function withoutAttribute($name)
    {
        $new = clone $this;

        unset($new->attributes[$name]);

        return $new;
    }
}

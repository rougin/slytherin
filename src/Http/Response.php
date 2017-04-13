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

/**
 * Response
 *
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Response extends Message implements \Psr\Http\Message\ResponseInterface
{
    /**
     * @var integer
     */
    private $code;

    /**
     * @var array
     */
    private $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
    );

    /**
     * @var string
     */
    private $reason;

    /**
     * @param integer                                $code
     * @param \Psr\Http\Mesasge\StreamInterface|null $body
     * @param string                                 $version
     * @param array                                  $headers
     */
    public function __construct($code = 200, StreamInterface $body = null, array $headers = array(), $version = '1.1')
    {
        parent::__construct($body, $headers, $version);

        $this->code = $code;

        $this->reason = $this->codes[$code];
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * @throws \InvalidArgumentException
     *
     * @param  int $code
     * @param  string $reason
     * @return static
     */
    public function withStatus($code, $reason = '')
    {
        $this->code = $code;

        $this->reason = ($reason == '') ? $this->codes[$code] : $reason;

        return clone $this;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->reason;
    }
}

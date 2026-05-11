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

use Psr\Http\Message\ResponseInterface;

/**
 * @package Slytherin
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Response extends Message implements ResponseInterface
{
    /**
     * @var integer
     */
    protected $code = 200;

    /**
     * @var array<integer, string>
     */
    protected $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi Status',
        208 => 'Already Reported',
        226 => 'Im Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'Uri Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'Im A Teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    );

    /**
     * @var string
     */
    protected $reason = 'OK';

    /**
     * Initializes the response instance.
     *
     * @param integer                                $code
     * @param \Psr\Http\Message\StreamInterface|null $body
     * @param array<string, string[]>                $headers
     * @param string                                 $version
     *
     * @todo Remove usage of "null" in this method.
     */
    public function __construct($code = 200, $body = null, array $headers = array(), $version = '1.1')
    {
        parent::__construct($body, $headers, $version);

        $this->code = $code;

        $this->reason = $this->codes[$code];
    }

    /**
     * Returns the response reason phrase associated with the status code.
     *
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->reason;
    }

    /**
     * Returns the response status code.
     *
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->code;
    }

    /**
     * Returns an instance with the specified status
     * code and, optionally, reason phrase.
     *
     * @param integer $code
     * @param string  $reasonPhrase
     *
     * @return static
     * @throws \InvalidArgumentException
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        if ($code < 100 || $code > 599)
        {
            $text = 'Status code must be an integer between 100 and 599.';

            throw new \InvalidArgumentException($text);
        }

        $static = clone $this;

        $static->code = $code;

        $static->reason = $reasonPhrase ?: $static->codes[$code];

        return $static;
    }
}

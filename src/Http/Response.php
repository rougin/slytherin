<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\ResponseInterface;

Interop::register('Response');

/**
 * @package Slytherin
 *
 * @method string                              getReasonPhrase()
 * @method integer                             getStatusCode()
 * @method \Psr\Http\Message\ResponseInterface withStatus(integer $code, string $reasonPhrase = '')
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Response extends PsrResponse implements ResponseInterface
{
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
}

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
}

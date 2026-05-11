<?php

namespace Rougin\Slytherin\Http;

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
class Response extends PsrResponse
{
}

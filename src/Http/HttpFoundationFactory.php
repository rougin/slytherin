<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory as HttpFactory;

/**
 * Application
 *
 * Creates Symfony Request and Response instances from PSR-7 ones.
 * This is a temporary fix for the issue in getting URI in a Symfony Response.
 * 
 * @package Slytherin
 * @author  Kévin Dunglas <dunglas@gmail.com>
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HttpFoundationFactory extends HttpFactory
{
    /**
     * Creates a Symfony Request instance from a PSR-7 one.
     * 
     * @param  \Psr\Http\Message\ServerRequestInterface $psrRequest
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function createRequest(ServerRequestInterface $psrRequest)
    {
        $server = [];
        $uri = $psrRequest->getUri();

        $server['SERVER_NAME']  = $uri->getHost();
        $server['SERVER_PORT']  = $uri->getPort();
        $server['REQUEST_URI']  = $uri->getPath();
        $server['QUERY_STRING'] = $uri->getQuery();

        $server = array_replace($server, $psrRequest->getServerParams());

        return new Request(
            (array) $psrRequest->getQueryParams(),
            (array) $psrRequest->getParsedBody(),
            (array) $psrRequest->getAttributes(),
            (array) $psrRequest->getCookieParams(),
            (array) $psrRequest->getUploadedFiles(),
            (array) $server,
            $psrRequest->getBody()->__toString()
        );
    }
}

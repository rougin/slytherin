<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Rougin\Slytherin\System;
use Zend\Diactoros\Response as ZendResponse;
use Zend\Diactoros\ServerRequestFactory;

/**
 * An integration for Slytherin's simple HTTP package.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class HttpIntegration implements IntegrationInterface
{
    /**
     * @var string|null
     */
    protected $preferred = null;

    /**
     * Defines the specified integration.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Rougin\Slytherin\Integration\Configuration    $config
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        /** @var array<string, string> */
        $server = $config->get('app.http.server', $_SERVER);

        /** @var array<string, string> */
        $cookies = $config->get('app.http.cookies', $_COOKIE);

        /** @var array<string, string> */
        $query = $config->get('app.http.get', $_GET);

        /** @var array<string, array<string, string[]>> */
        $files = $config->get('app.http.files', $_FILES);

        /** @var array<string, mixed>|object|null */
        $parsed = $config->get('app.http.post', $_POST);

        $headers = $this->headers($server);

        $request = new ServerRequest($server, $cookies, $query, $files, $parsed);

        foreach ($headers as $key => $value)
        {
            $request = $request->withHeader($key, $value);
        }

        return $this->resolve($container, $request);
    }

    /**
     * Converts $_SERVER parameters to message
     * header values.
     *
     * @param array<string, string> $server
     *
     * @return array<string, string>
     */
    protected function headers(array $server)
    {
        $headers = array();

        foreach ($server as $key => $value)
        {
            $http = strpos($key, 'HTTP_') === 0;

            $string = strtolower(substr($key, 5));

            $string = str_replace('_', ' ', $string);

            $string = ucwords(strtolower($string));

            $key = str_replace(' ', '-', $string);

            $http && $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * Checks on what object will be defined to
     * container.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     * @param \Psr\Http\Message\ServerRequestInterface       $request
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    protected function resolve(ContainerInterface $container, ServerRequestInterface $request)
    {
        $response = new Response;

        $class = 'Zend\Diactoros\ServerRequestFactory';

        $empty = $this->preferred === null;

        $wantZend = $this->preferred === 'diactoros';

        if (($empty || $wantZend) && class_exists($class))
        {
            $response = new ZendResponse;

            $request = ServerRequestFactory::fromGlobals();
        }

        if (! $container->has(System::REQUEST))
        {
            $container->set(System::REQUEST, $request);
        }

        if (! $container->has(System::RESPONSE))
        {
            $container->set(System::RESPONSE, $response);
        }

        return $container;
    }

}

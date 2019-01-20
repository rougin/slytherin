<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;
use Zend\Diactoros\Response as ZendResponse;
use Zend\Diactoros\ServerRequestFactory;

/**
 * HTTP Integration
 *
 * An integration for Slytherin's simple HTTP package.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class HttpIntegration implements IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        list($server, $cookies, $get, $files, $post) = $this->globals($config);

        $headers = (array) $this->headers($server);

        $request = new ServerRequest($server, $cookies, $get, $files, $post);

        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        return $this->resolve($container, $request, new Response);
    }

    /**
     * Returns the PHP's global variables.
     *
     * @param  \Rougin\Slytherin\Integration\Configuration $config
     * @return array
     */
    protected function globals(Configuration $config)
    {
        $cookies = $config->get('app.http.cookies', array());

        $files = $config->get('app.http.files', array());

        $get = $config->get('app.http.get', array());

        $post = $config->get('app.http.post', array());

        $server = $config->get('app.http.server', $this->server());

        return array($server, $cookies, $get, $files, $post);
    }

    /**
     * Converts $_SERVER parameters to message header values.
     *
     * @param  array $server
     * @return array
     */
    protected function headers(array $server)
    {
        $headers = array();

        foreach ((array) $server as $key => $value) {
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
     * Checks on what object will be defined to container.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Psr\Http\Message\ServerRequestInterface       $request
     * @param  \Psr\Http\Message\ResponseInterface            $response
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    protected function resolve(ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response)
    {
        if (class_exists('Zend\Diactoros\ServerRequestFactory')) {
            $response = new ZendResponse;

            $request = ServerRequestFactory::fromGlobals();
        }

        $container->set('Psr\Http\Message\ServerRequestInterface', $request);

        return $container->set('Psr\Http\Message\ResponseInterface', $response);
    }

    /**
     * Returns a sample $_SERVER values.
     *
     * @return array
     */
    protected function server()
    {
        $server = array('SERVER_PORT' => 8000);

        $server['REQUEST_METHOD'] = 'GET';

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'localhost';

        $server['HTTP_CONTENT_TYPE'] = 'text/plain';

        return $server;
    }
}

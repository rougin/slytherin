<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * HTTP Integration
 *
 * An integration for Slytherin's simple HTTP package.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HttpIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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

        $request = new \Rougin\Slytherin\Http\ServerRequest($server, $cookies, $get, $files, $post);

        $response = new \Rougin\Slytherin\Http\Response;

        $headers = function_exists('xdebug_get_headers') ? xdebug_get_headers() : headers_list();

        foreach ($headers as $header) {
            list($key, $value) = explode(': ', $header);

            $request = $request->withHeader($key, $value);
        }

        return $this->resolve($container, $request, $response);
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
            $request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();

            $response = new \Zend\Diactoros\Response;
        }

        $container->set('Psr\Http\Message\ServerRequestInterface', $request);

        $container->set('Psr\Http\Message\ResponseInterface', $response);

        return $container;
    }

    /**
     * Returns a sample $_SERVER values.
     *
     * @return array
     */
    protected function server()
    {
        $server = array();

        $server['REQUEST_METHOD']  = 'GET';

        $server['REQUEST_URI'] = '/';

        $server['SERVER_NAME'] = 'localhost';

        $server['SERVER_PORT'] = '8000';

        return $server;
    }
}

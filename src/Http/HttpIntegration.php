<?php

namespace Rougin\Slytherin\Http;

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
        list($server, $cookies, $get, $files, $post) = $this->getGlobalVariables($config);

        $request  = new \Rougin\Slytherin\Http\ServerRequest($server, $cookies, $get, $files, $post);
        $response = new \Rougin\Slytherin\Http\Response;

        $headers = function_exists('xdebug_get_headers') ? xdebug_get_headers() : headers_list();

        foreach ($headers as $header) {
            list($key, $value) = explode(': ', $header);

            $request = $request->withHeader($key, $value);
        }

        $container->set('Psr\Http\Message\ServerRequestInterface', $request);
        $container->set('Psr\Http\Message\ResponseInterface', $response);

        return $container;
    }

    /**
     * Returns the PHP's global variables.
     *
     * @param  \Rougin\Slytherin\Integration\Configuration $config
     * @return array
     */
    protected function getGlobalVariables(Configuration $config)
    {
        $cookies = $config->get('app.http.cookies', array());
        $files   = $config->get('app.http.files', array());
        $get     = $config->get('app.http.get', array());
        $post    = $config->get('app.http.post', array());
        $server  = $config->get('app.http.server', $this->getSampleServer());

        return array($server, $cookies, $get, $files, $post);
    }

    /**
     * Returns a sample $_SERVER values.
     *
     * @return array
     */
    protected function getSampleServer()
    {
        $server = array();

        $server['REQUEST_METHOD']  = 'GET';
        $server['REQUEST_URI']     = '/';
        $server['SERVER_NAME']     = 'localhost';
        $server['SERVER_PORT']     = '8000';

        return $server;
    }
}

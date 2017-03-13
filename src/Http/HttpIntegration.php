<?php

namespace Rougin\Slytherin\Http;

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
     * @param  array                                          $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container, array $config = array())
    {
        $cookies = $config['app']['http']['cookies'];
        $files   = $config['app']['http']['files'];
        $get     = $config['app']['http']['get'];
        $post    = $config['app']['http']['post'];
        $server  = $config['app']['http']['server'];

        $request  = new \Rougin\Slytherin\Http\ServerRequest($server, $cookies, $get, $files, $post);
        $response = new \Rougin\Slytherin\Http\Response;

        $original = headers_list();

        if (function_exists('xdebug_get_headers')) {
            $original = xdebug_get_headers();
        }

        foreach ($original as $header) {
            list($key, $value) = explode(': ', $header);

            $request = $request->withHeader($key, $value);
        }

        $container->set('Psr\Http\Message\ServerRequestInterface', $request);
        $container->set('Psr\Http\Message\ResponseInterface', $response);

        return $container;
    }
}

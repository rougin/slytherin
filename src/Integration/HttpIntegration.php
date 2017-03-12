<?php

namespace Rougin\Slytherin\Integration;

/**
 * HTTP Integration
 *
 * An integration for Slytherin's simple HTTP package.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HttpIntegration implements IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  array                                          $configurations
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container, array $configurations = array())
    {
    	$request  = new \Rougin\Slytherin\Http\ServerRequest($_SERVER, $_COOKIE, $_GET, $_FILES, $_POST);
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

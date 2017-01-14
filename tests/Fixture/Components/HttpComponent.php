<?php

namespace Rougin\Slytherin\Fixture\Components;

/**
 * HTTP Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HttpComponent extends \Rougin\Slytherin\Component\AbstractComponent
{
    /**
     * Type of the component:
     * dispatcher, debugger, http, middleware
     *
     * @var string
     */
    protected $type = 'http';

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        $slash = DIRECTORY_SEPARATOR;
        $root  = str_replace($slash . 'tests' . $slash . 'Fixture' . $slash . 'Components', '', __DIR__);

        $server = array(
            'DOCUMENT_ROOT'   => $root,
            'REMOTE_ADDR'     => '127.0.0.1',
            'SCRIPT_FILENAME' => '/var/www/html/slytherin/index.php',
            'SCRIPT_NAME'     => '/slytherin/index.php'
        );

        $this->request  = new \Rougin\Slytherin\Http\ServerRequest('1.1', array(), null, '/', 'GET', null, $server);
        $this->response = new \Rougin\Slytherin\Http\Response;

        return array($this->request, $this->response);
    }

    /**
     * Sets the component. Can also add it to the container.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @return void
     */
    public function set(\Interop\Container\ContainerInterface &$container)
    {
        $container->add('Psr\Http\Message\ServerRequestInterface', $this->request);
        $container->add('Psr\Http\Message\ResponseInterface', $this->response);
    }
}

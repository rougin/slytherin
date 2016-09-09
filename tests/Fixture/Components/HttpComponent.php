<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Interop\Container\ContainerInterface;

use Rougin\Slytherin\Test\Fixture\Http\Response;
use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Test\Fixture\Http\ServerRequest;

/**
 * HTTP Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class HttpComponent extends AbstractComponent
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
        $slash  = DIRECTORY_SEPARATOR;
        $root   = str_replace($slash . 'tests' . $slash . 'Fixture' . $slash . 'Components', '', __DIR__);
        $server = [ 'REMOTE_ADDR' => '127.0.0.1', 'DOCUMENT_ROOT' => $root ];

        $this->request  = new ServerRequest('1.1', [], null, '/', 'GET', null, $server);
        $this->response = new Response;

        return [ $this->request, $this->response ];
    }

    /**
     * Sets the component. Can also add it to the container.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @return void
     */
    public function set(ContainerInterface &$container)
    {
        $container->add('Psr\Http\Message\ServerRequestInterface', $this->request);
        $container->add('Psr\Http\Message\ResponseInterface', $this->response);
    }
}

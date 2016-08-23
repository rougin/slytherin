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
     *
     * @return mixed
     */
    public function get()
    {
        $this->request  = new ServerRequest;
        $this->response = new Response;

        return [ $this->request, $this->response ];
    }

    /**
     * Sets the component and add it to the container of your choice.
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

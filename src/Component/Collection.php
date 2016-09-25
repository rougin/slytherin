<?php

namespace Rougin\Slytherin\Component;

use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

use Rougin\Slytherin\Debug\DebuggerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;
use Rougin\Slytherin\Dispatching\DispatcherInterface;

/**
 * Component Collection
 *
 * Contains all the required components for Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Collection
{
    /**
     * @var array
     */
    protected $components = [];

    /**
     * Gets an instance of the container.
     *
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->getComponent('container');
    }

    /**
     * Sets the container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        return $this->setComponent('container', $container);
    }

    /**
     * Gets the dispatcher.
     *
     * @return \Rougin\Slytherin\Dispatching\DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->getComponent('dispatcher');
    }

    /**
     * Sets the dispatcher.
     *
     * @param \Rougin\Slytherin\Dispatching\DispatcherInterface $dispatcher
     */
    public function setDispatcher(DispatcherInterface $dispatcher)
    {
        return $this->setComponent('dispatcher', $dispatcher);
    }

    /**
     * Gets the debugger.
     *
     * @return \Rougin\Slytherin\Debugger\DebuggerInterface
     */
    public function getDebugger()
    {
        return $this->getComponent('debugger');
    }

    /**
     * Sets the debugger.
     *
     * @param \Rougin\Slytherin\Debugger\DebuggerInterface $debugger
     */
    public function setDebugger(DebuggerInterface $debugger)
    {
        return $this->setComponent('debugger', $debugger);
    }

    /**
     * Gets the HTTP components.
     *
     * @return mixed
     */
    public function getHttp()
    {
        return [ $this->getHttpRequest(), $this->getHttpResponse() ];
    }

    /**
     * Sets the HTTP components.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @return array
     */
    public function setHttp(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->setComponent('request', $request);

        return $this->setComponent('response', $response);
    }

    /**
     * Gets the middlware.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface
     */
    public function getMiddleware()
    {
        return $this->getComponent('middlware');
    }

    /**
     * Sets the middlware.
     *
     * @param \Rougin\Slytherin\Middleware\MiddlewareInterface $middlware
     */
    public function setMiddleware(MiddlewareInterface $middlware)
    {
        return $this->setComponent('middlware', $middlware);
    }

    /**
     * Gets the request.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getHttpRequest()
    {
        return $this->getComponent('request');
    }

    /**
     * Gets the response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        return $this->getComponent('response');
    }

    /**
     * Gets the selected component.
     *
     * @param  string $type
     * @return mixed
     */
    private function getComponent($type)
    {
        if (! isset($this->components[$type])) {
            return null;
        }

        return $this->components[$type];
    }

    /**
     * Sets the selected component.
     *
     * @param string $type
     * @param mixed  $component
     */
    private function setComponent($type, $component)
    {
        $this->components[$type] = $component;

        return $this;
    }
}

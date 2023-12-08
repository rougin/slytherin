<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Debug\DebuggerInterface;
use Rougin\Slytherin\Dispatching\DispatcherInterface;
use Rougin\Slytherin\IoC\ContainerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;

/**
 * Component Collection
 *
 * Contains all the required components for Slytherin.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Components
{
    /**
     * @var array
     */
    protected $components = [];

    /**
     * Gets an instance of the container.
     * 
     * @return \Rougin\Slytherin\IoC\ContainerInterface
     */
    public function getContainer()
    {
        return $this->getComponent('container');
    }

    /**
     * Sets the container.
     * 
     * @param \Rougin\Slytherin\IoC\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        return $this->setComponent('container', $container);
    }

    /**
     * Gets an instance of the dependency injector.
     * NOTE: To be removed in v1.0.0. Use "getContainer" instead.
     * 
     * @return \Rougin\Slytherin\IoC\ContainerInterface
     */
    public function getDependencyInjector()
    {
        return $this->getContainer();
    }

    /**
     * Sets the dependency injector.
     * NOTE: To be removed in v1.0.0. Use "setContainer" instead.
     * 
     * @param \Rougin\Slytherin\IoC\ContainerInterface $injector
     */
    public function setDependencyInjector(ContainerInterface $injector)
    {
        return $this->setContainer($injector);
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
     * @param  \Rougin\Slytherin\Debugger\DebuggerInterface $debugger
     */
    public function setDebugger(DebuggerInterface $debugger)
    {
        return $this->setComponent('debugger', $debugger);
    }

    /**
     * Gets the debugger.
     * NOTE: To be removed in v1.0.0. Use "getDebugger" instead.
     * 
     * @return \Rougin\Slytherin\Debug\DebuggerInterface
     */
    public function getErrorHandler()
    {
        return $this->getDebugger();
    }

    /**
     * Sets the debugger.
     * NOTE: To be removed in v1.0.0. Use "setDebugger" instead.
     * 
     * @param  \Rougin\Slytherin\Debug\DebuggerInterface $debugger
     */
    public function setErrorHandler(DebuggerInterface $debugger)
    {
        return $this->setDebugger($debugger);
    }

    /**
     * Gets the HTTP components.
     * 
     * @return mixed
     */
    public function getHttp()
    {
        return [
            $this->getComponent('request'),
            $this->getComponent('response')
        ];
    }

    /**
     * Sets the HTTP components.
     * 
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface $response
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
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middlware
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
        if ( ! isset($this->components[$type])) {
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

<?php

namespace Rougin\Slytherin\Component;

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
    protected $components = array();

    /**
     * Gets an instance of the container.
     *
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->get('container');
    }

    /**
     * Sets the container.
     *
     * @param  \Interop\Container\ContainerInterface $container
     * @return self
     */
    public function setContainer(\Interop\Container\ContainerInterface $container)
    {
        return $this->set('container', $container);
    }

    /**
     * Gets the dispatcher.
     *
     * @return \Rougin\Slytherin\Dispatching\DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->get('dispatcher');
    }

    /**
     * Sets the dispatcher.
     *
     * @param  \Rougin\Slytherin\Dispatching\DispatcherInterface $dispatcher
     * @return self
     */
    public function setDispatcher(\Rougin\Slytherin\Dispatching\DispatcherInterface $dispatcher)
    {
        return $this->set('dispatcher', $dispatcher);
    }

    /**
     * Gets the debugger.
     *
     * @return \Rougin\Slytherin\Debugger\DebuggerInterface
     */
    public function getDebugger()
    {
        return $this->get('debugger');
    }

    /**
     * Sets the debugger.
     *
     * @param  \Rougin\Slytherin\Debugger\DebuggerInterface $debugger
     * @return self
     */
    public function setDebugger(\Rougin\Slytherin\Debug\DebuggerInterface $debugger)
    {
        return $this->set('debugger', $debugger);
    }

    /**
     * Gets the HTTP components.
     *
     * @return mixed
     */
    public function getHttp()
    {
        return array($this->get('request'), $this->get('response'));
    }

    /**
     * Sets the HTTP components.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @return self
     */
    public function setHttp(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response)
    {
        $this->set('request', $request);

        return $this->set('response', $response);
    }

    /**
     * Gets the middleware.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface
     */
    public function getMiddleware()
    {
        return $this->get('middleware');
    }

    /**
     * Sets the middleware.
     *
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @return self
     */
    public function setMiddleware(\Rougin\Slytherin\Middleware\MiddlewareInterface $middleware)
    {
        return $this->set('middleware', $middleware);
    }

    /**
     * Gets the request.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getHttpRequest()
    {
        return $this->get('request');
    }

    /**
     * Gets the response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        return $this->get('response');
    }

    /**
     * Gets the selected component.
     *
     * @param  string $type
     * @return mixed
     */
    protected function get($type)
    {
        return isset($this->components[$type]) ? $this->components[$type] : null;
    }

    /**
     * Sets the selected component.
     *
     * @param  string $type
     * @param  mixed  $component
     * @return self
     */
    protected function set($type, $component)
    {
        $this->components[$type] = $component;

        return $this;
    }
}

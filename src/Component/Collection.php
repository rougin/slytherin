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
     * @return \Rougin\Slytherin\Routing\DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->get('dispatcher');
    }

    /**
     * Sets the dispatcher.
     *
     * @param  \Rougin\Slytherin\Routing\DispatcherInterface $dispatcher
     * @return self
     */
    public function setDispatcher(\Rougin\Slytherin\Routing\DispatcherInterface $dispatcher)
    {
        return $this->set('dispatcher', $dispatcher);
    }

    /**
     * Gets the debugger.
     *
     * @return \Rougin\Slytherin\Debug\DebuggerInterface
     */
    public function getDebugger()
    {
        return $this->getErrorHandler();
    }

    /**
     * Sets the debugger.
     *
     * @param  \Rougin\Slytherin\Debug\ErrorHandlerInterface $debugger
     * @return self
     */
    public function setDebugger(\Rougin\Slytherin\Debug\ErrorHandlerInterface $debugger)
    {
        return $this->setErrorHandler($debugger);
    }

    /**
     * Gets the error handler.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface
     */
    public function getErrorHandler()
    {
        return $this->get('error_handler');
    }

    /**
     * Sets the error handler.
     *
     * @param  \Rougin\Slytherin\Debug\ErrorHandlerInterface $errorHandler
     * @return self
     */
    public function setErrorHandler(\Rougin\Slytherin\Debug\ErrorHandlerInterface $errorHandler)
    {
        return $this->set('error_handler', $errorHandler);
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
     * Gets the HTTP request.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getHttpRequest()
    {
        return $this->get('request');
    }

    /**
     * Sets the HTTP request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return self
     */
    public function setHttpRequest(\Psr\Http\Message\ServerRequestInterface $request)
    {
        return $this->set('request', $request);
    }

    /**
     * Gets the HTTP response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        return $this->get('response');
    }

    /**
     * Sets the HTTP response.
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return self
     */
    public function setHttpResponse(\Psr\Http\Message\ResponseInterface $response)
    {
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

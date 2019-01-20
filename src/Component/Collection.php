<?php

namespace Rougin\Slytherin\Component;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\VanillaContainer;
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Middleware\DispatcherInterface as MiddlewareDispatcher;
use Rougin\Slytherin\Routing\DispatcherInterface as RouteDispatcher;

/**
 * Component Collection
 *
 * Contains all the required components for Slytherin.
 * NOTE: To be removed in v1.0.0. Use "Integration" instead.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Collection extends VanillaContainer
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Gets an instance of the container.
     *
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer()
    {
        $interface = 'Psr\Container\ContainerInterface';

        return (is_a($this->container, $interface)) ? $this->container : $this;
    }

    /**
     * Sets the container.
     *
     * @param  \Psr\Container\ContainerInterface $container
     * @return self
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Gets the dispatcher.
     *
     * @return \Rougin\Slytherin\Routing\DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->get('Rougin\Slytherin\Routing\DispatcherInterface');
    }

    /**
     * Sets the dispatcher.
     *
     * @param  \Rougin\Slytherin\Routing\DispatcherInterface $dispatcher
     * @return self
     */
    public function setDispatcher(RouteDispatcher $dispatcher)
    {
        return $this->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);
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
    public function setDebugger(ErrorHandlerInterface $debugger)
    {
        return $this->setErrorHandler($debugger);
    }

    /**
     * Gets the error handler.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface|null
     */
    public function getErrorHandler()
    {
        $interface = 'Rougin\Slytherin\Debug\ErrorHandlerInterface';

        return ($this->getContainer()->has($interface)) ? $this->getContainer()->get($interface) : null;
    }

    /**
     * Sets the error handler.
     *
     * @param  \Rougin\Slytherin\Debug\ErrorHandlerInterface $errorHandler
     * @return self
     */
    public function setErrorHandler(\Rougin\Slytherin\Debug\ErrorHandlerInterface $errorHandler)
    {
        return $this->set('Rougin\Slytherin\Debug\ErrorHandlerInterface', $errorHandler);
    }

    /**
     * Gets the HTTP components.
     *
     * @return mixed
     */
    public function getHttp()
    {
        $request  = $this->get('Psr\Http\Message\ServerRequestInterface');
        $response = $this->get('Psr\Http\Message\ResponseInterface');

        return array($request, $response);
    }

    /**
     * Sets the HTTP components.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @param  \Psr\Http\Message\ResponseInterface      $response
     * @return self
     */
    public function setHttp(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->set('Psr\Http\Message\ServerRequestInterface', $request);

        return $this->set('Psr\Http\Message\ResponseInterface', $response);
    }

    /**
     * Gets the HTTP request.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getHttpRequest()
    {
        return $this->get('Psr\Http\Message\ServerRequestInterface');
    }

    /**
     * Sets the HTTP request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return self
     */
    public function setHttpRequest(ServerRequestInterface $request)
    {
        return $this->set('Psr\Http\Message\ServerRequestInterface', $request);
    }

    /**
     * Gets the HTTP response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        return $this->get('Psr\Http\Message\ResponseInterface');
    }

    /**
     * Sets the HTTP response.
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return self
     */
    public function setHttpResponse(ResponseInterface $response)
    {
        return $this->set('Psr\Http\Message\ResponseInterface', $response);
    }

    /**
     * Gets the middleware.
     *
     * @return \Rougin\Slytherin\Middleware\DispatcherInterface|null
     */
    public function getMiddleware()
    {
        $interface = 'Rougin\Slytherin\Middleware\DispatcherInterface';

        return ($this->getContainer()->has($interface)) ? $this->getContainer()->get($interface) : null;
    }

    /**
     * Sets the middleware.
     *
     * @param  \Rougin\Slytherin\Middleware\DispatcherInterface $middleware
     * @return self
     */
    public function setMiddleware(MiddlewareDispatcher $middleware)
    {
        return $this->set('Rougin\Slytherin\Middleware\DispatcherInterface', $middleware);
    }
}

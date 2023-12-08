<?php

namespace Rougin\Slytherin\Component;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\VanillaContainer;
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Middleware\DispatcherInterface as Middleware;
use Rougin\Slytherin\Routing\DispatcherInterface as Routing;
use Rougin\Slytherin\System;

/**
 * Component Collection
 *
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
        return (is_a($this->container, System::CONTAINER)) ? $this->container : $this;
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
     * @return \Rougin\Slytherin\Routing\DispatcherInterface
     */
    public function getDispatcher()
    {
        /** @var \Rougin\Slytherin\Routing\DispatcherInterface */
        return $this->get(System::DISPATCHER);
    }

    /**
     * Sets the dispatcher.
     *
     * @param  \Rougin\Slytherin\Routing\DispatcherInterface $dispatcher
     * @return self
     */
    public function setDispatcher(Routing $dispatcher)
    {
        $this->set(System::DISPATCHER, $dispatcher);

        return $this;
    }

    /**
     * Gets the debugger.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface|null
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
        $this->setErrorHandler($debugger);

        return $this;
    }

    /**
     * Gets the error handler.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface|null
     */
    public function getErrorHandler()
    {
        if (! $this->has(System::ERREPORT)) return null;

        /** @var \Rougin\Slytherin\Debug\ErrorHandlerInterface */
        return $this->get(System::ERREPORT);
    }

    /**
     * Sets the error handler.
     *
     * @param  \Rougin\Slytherin\Debug\ErrorHandlerInterface $errorHandler
     * @return self
     */
    public function setErrorHandler(ErrorHandlerInterface $errorHandler)
    {
        $this->set(System::ERREPORT, $errorHandler);

        return $this;
    }

    /**
     * Gets the HTTP components.
     *
     * @return mixed
     */
    public function getHttp()
    {
        return array($this->getHttpRequest(), $this->getHttpResponse());
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
        $this->set(System::SERVER_REQUEST, $request);

        $this->set(System::RESPONSE, $response);

        return $this;
    }

    /**
     * Gets the HTTP request.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getHttpRequest()
    {
        /** @var \Psr\Http\Message\ServerRequestInterface */
        return $this->get(System::SERVER_REQUEST);
    }

    /**
     * Sets the HTTP request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return self
     */
    public function setHttpRequest(ServerRequestInterface $request)
    {
        $this->set(System::SERVER_REQUEST, $request);

        return $this;
    }

    /**
     * Gets the HTTP response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        /** @var \Psr\Http\Message\ResponseInterface */
        return $this->get(System::RESPONSE);
    }

    /**
     * Sets the HTTP response.
     *
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return self
     */
    public function setHttpResponse(ResponseInterface $response)
    {
        $this->set(System::RESPONSE, $response);

        return $this;
    }

    /**
     * TODO: Reimplement Middleware package.
     *
     * Gets the middleware.
     *
     * @return \Rougin\Slytherin\Middleware\DispatcherInterface|null
     */
    public function getMiddleware()
    {
        if (! $this->has(System::MIDDLEWARE)) return null;

        /** @var \Rougin\Slytherin\Middleware\DispatcherInterface */
        return $this->get(System::MIDDLEWARE);
    }

    /**
     * TODO: Reimplement Middleware package.
     *
     * Sets the middleware.
     *
     * @param  \Rougin\Slytherin\Middleware\DispatcherInterface $middleware
     * @return self
     */
    public function setMiddleware(Middleware $middleware)
    {
        $this->set(System::MIDDLEWARE, $middleware);

        return $this;
    }
}

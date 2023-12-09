<?php

namespace Rougin\Slytherin\Component;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Middleware\DispatcherInterface as Middleware;
use Rougin\Slytherin\Routing\DispatcherInterface as Routing;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\RendererInterface;

/**
 * Component Collection
 *
 * NOTE: To be removed in v1.0.0. Use "Integration" instead.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Collection implements ContainerInterface
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var mixed[]
     */
    protected $items = array();

    public function __construct()
    {
        $this->container = new Container;
    }

    /**
     * Adds a new instance to the container.
     * NOTE: To be removed in v1.0.0. Use "set" instead.
     *
     * @param  string $id
     * @param  object $concrete
     * @return self
     */
    public function add($id, $concrete)
    {
        return $this->set($id, $concrete);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param  string $id
     * @return object
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function get($id)
    {
        /** @var object */
        return $this->container->get($id);
    }

    /**
     * Gets an instance of the container.
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Gets the debugger.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface
     */
    public function getDebugger()
    {
        /** @var \Rougin\Slytherin\Debug\ErrorHandlerInterface */
        return $this->get(System::DEBUGGER);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "getContainer" instead.
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function getDependencyInjector()
    {
        return $this->getContainer();
    }

    /**
     * Gets the dispatcher.
     *
     * @return \Rougin\Slytherin\Dispatching\DispatcherInterface
     */
    public function getDispatcher()
    {
        /** @var \Rougin\Slytherin\Dispatching\DispatcherInterface */
        return $this->get(System::DISPATCHER);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "getDebugger" instead.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface
     */
    public function getErrorHandler()
    {
        return $this->getDebugger();
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
     * Gets the request.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getHttpRequest()
    {
        /** @var \Psr\Http\Message\ServerRequestInterface */
        return $this->get(System::REQUEST);
    }

    /**
     * Gets the response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        /** @var \Psr\Http\Message\ResponseInterface */
        return $this->get(System::RESPONSE);
    }

    /**
     * Gets the middleware.
     *
     * @return \Rougin\Slytherin\Middleware\DispatcherInterface
     */
    public function getMiddleware()
    {
        /** @var \Rougin\Slytherin\Middleware\DispatcherInterface */
        return $this->get(System::MIDDLEWARE);
    }

    /**
     * Gets the template.
     *
     * @return \Rougin\Slytherin\Template\RendererInterface
     */
    public function getTemplate()
    {
        /** @var \Rougin\Slytherin\Template\RendererInterface */
        return $this->get(System::TEMPLATE);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     *
     * @param  string $id
     * @return boolean
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Sets a new instance to the container.
     *
     * @param  string $id
     * @param  mixed  $concrete
     * @return self
     */
    public function set($id, $concrete = null)
    {
        $this->container->set($id, $concrete);

        return $this;
    }

    /**
     * Sets the container.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @return self
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Sets the debugger.
     *
     * @param  \Rougin\Slytherin\Debug\ErrorHandlerInterface $debugger
     * @return self
     */
    public function setDebugger(ErrorHandlerInterface $debugger)
    {
        return $this->add(System::DEBUGGER, $debugger);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "setContainer" instead.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $injector
     * @return self
     */
    public function setDependencyInjector(ContainerInterface $injector)
    {
        return $this->setContainer($injector);
    }

    /**
     * Sets the dispatcher.
     *
     * @param  \Rougin\Slytherin\Dispatching\DispatcherInterface $dispatcher
     * @return self
     */
    public function setDispatcher(Routing $dispatcher)
    {
        return $this->add(System::DISPATCHER, $dispatcher);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "setDebugger" instead.
     *
     * @param  \Rougin\Slytherin\Debug\ErrorHandlerInterface $debugger
     * @return self
     */
    public function setErrorHandler(ErrorHandlerInterface $debugger)
    {
        return $this->setDebugger($debugger);
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
        $this->add(System::REQUEST, $request);

        return $this->add(System::RESPONSE, $response);
    }

    /**
     * Sets the HTTP request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return self
     */
    public function setHttpRequest(ServerRequestInterface $request)
    {
        $this->set(System::REQUEST, $request);

        return $this;
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
     * Sets the middleware.
     *
     * @param  \Rougin\Slytherin\Middleware\DispatcherInterface $middleware
     * @return self
     */
    public function setMiddleware(Middleware $middleware)
    {
        return $this->add(System::MIDDLEWARE, $middleware);
    }

    /**
     * Sets the template.
     *
     * @param  \Rougin\Slytherin\Template\RendererInterface $template
     * @return self
     */
    public function setTemplate(RendererInterface $template)
    {
        return $this->add(System::TEMPLATE, $template);
    }
}

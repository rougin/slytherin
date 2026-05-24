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
use Rougin\Slytherin\System\Errors\DebuggerNotFound;
use Rougin\Slytherin\System\Errors\DispatcherNotFound;
use Rougin\Slytherin\System\Errors\MiddlewareNotFound;
use Rougin\Slytherin\System\Errors\RequestNotFound;
use Rougin\Slytherin\System\Errors\ResponseNotFound;
use Rougin\Slytherin\System\Errors\TemplateNotFound;
use Rougin\Slytherin\Template\RendererInterface;

Interop::register('Collection');

/**
 * @package Slytherin
 *
 * @property \Rougin\Slytherin\Container\ContainerInterface $container
 *
 * @method mixed                                  get(string $id)
 * @method boolean                                has(string $id)
 * @method \Rougin\Slytherin\Component\Collection set(string $id, $concrete = null)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Collection extends PsrCollection implements ContainerInterface
{
    public function __construct()
    {
        $this->container = new Container;
    }

    /**
     * @deprecated since ~0.9, use "set" instead.
     *
     * Adds an instance to the container.
     *
     * @param string $id
     * @param object $concrete
     *
     * @return self
     */
    public function add($id, $concrete)
    {
        return $this->set($id, $concrete);
    }

    /**
     * Returns the container.
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Returns the debugger.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface
     */
    public function getDebugger()
    {
        $debugger = $this->get(System::DEBUGGER);

        if (! $debugger instanceof ErrorHandlerInterface)
        {
            throw new DebuggerNotFound($debugger);
        }

        return $debugger;
    }

    /**
     * @deprecated since ~0.4, use "getContainer" instead.
     *
     * Returns the dependency injector.
     *
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function getDependencyInjector()
    {
        return $this->getContainer();
    }

    /**
     * Returns the routing dispatcher.
     *
     * @return \Rougin\Slytherin\Routing\DispatcherInterface
     */
    public function getDispatcher()
    {
        $dispatcher = $this->get(System::DISPATCHER);

        if (! $dispatcher instanceof Routing)
        {
            throw new DispatcherNotFound($dispatcher);
        }

        return $dispatcher;
    }

    /**
     * @deprecated since ~0.4, use "getDebugger" instead.
     *
     * Returns the error handler.
     *
     * @return \Rougin\Slytherin\Debug\ErrorHandlerInterface
     */
    public function getErrorHandler()
    {
        return $this->getDebugger();
    }

    /**
     * Returns the HTTP request and response.
     *
     * @return array<integer, mixed>
     */
    public function getHttp()
    {
        $items = array();

        $items[] = $this->getHttpRequest();
        $items[] = $this->getHttpResponse();

        return $items;
    }

    /**
     * Returns the HTTP request.
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getHttpRequest()
    {
        $request = $this->get(System::REQUEST);

        if (! $request instanceof ServerRequestInterface)
        {
            throw new RequestNotFound($request);
        }

        return $request;
    }

    /**
     * Returns the HTTP response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        $response = $this->get(System::RESPONSE);

        if (! $response instanceof ResponseInterface)
        {
            throw new ResponseNotFound($response);
        }

        return $response;
    }

    /**
     * Returns the middleware.
     *
     * @return \Rougin\Slytherin\Middleware\DispatcherInterface
     */
    public function getMiddleware()
    {
        $middleware = $this->get(System::MIDDLEWARE);

        if (! $middleware instanceof Middleware)
        {
            throw new MiddlewareNotFound($middleware);
        }

        return $middleware;
    }

    /**
     * Returns the template.
     *
     * @return \Rougin\Slytherin\Template\RendererInterface
     */
    public function getTemplate()
    {
        $template = $this->get(System::TEMPLATE);

        if (! $template instanceof RendererInterface)
        {
            throw new TemplateNotFound($template);
        }

        return $template;
    }

    /**
     * Sets the container.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     *
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
     * @param \Rougin\Slytherin\Debug\ErrorHandlerInterface $debugger
     *
     * @return self
     */
    public function setDebugger(ErrorHandlerInterface $debugger)
    {
        return $this->add(System::DEBUGGER, $debugger);
    }

    /**
     * @deprecated since ~0.4, use "setContainer" instead.
     *
     * Sets the dependency injector.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $injector
     *
     * @return self
     */
    public function setDependencyInjector(ContainerInterface $injector)
    {
        return $this->setContainer($injector);
    }

    /**
     * Sets the routing dispatcher.
     *
     * @param \Rougin\Slytherin\Routing\DispatcherInterface $dispatcher
     *
     * @return self
     */
    public function setDispatcher(Routing $dispatcher)
    {
        return $this->add(System::DISPATCHER, $dispatcher);
    }

    /**
     * @deprecated since ~0.4, use "setDebugger" instead.
     *
     * Sets the error handler.
     *
     * @param \Rougin\Slytherin\Debug\ErrorHandlerInterface $debugger
     *
     * @return self
     */
    public function setErrorHandler(ErrorHandlerInterface $debugger)
    {
        return $this->setDebugger($debugger);
    }

    /**
     * Sets the HTTP components.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return self
     */
    public function setHttp(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->add(System::REQUEST, $request);

        $this->add(System::RESPONSE, $response);

        return $this;
    }

    /**
     * Sets the HTTP request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
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
     * @param \Psr\Http\Message\ResponseInterface $response
     *
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
     * @param \Rougin\Slytherin\Middleware\DispatcherInterface $middleware
     *
     * @return self
     */
    public function setMiddleware(Middleware $middleware)
    {
        return $this->add(System::MIDDLEWARE, $middleware);
    }

    /**
     * Sets the template.
     *
     * @param \Rougin\Slytherin\Template\RendererInterface $template
     *
     * @return self
     */
    public function setTemplate(RendererInterface $template)
    {
        return $this->add(System::TEMPLATE, $template);
    }
}

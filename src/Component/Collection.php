<?php

namespace Rougin\Slytherin\Component;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Debug\DebuggerInterface;
use Rougin\Slytherin\Dispatching\DispatcherInterface;
use Rougin\Slytherin\IoC\Container;
use Rougin\Slytherin\IoC\ContainerInterface;
use Rougin\Slytherin\Middleware\MiddlewareInterface;
use Rougin\Slytherin\Template\RendererInterface;

/**
 * Component Collection
 *
 * Contains all the required components for Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Collection implements ContainerInterface
{
    const CONTAINER = 'Rougin\Slytherin\IoC\ContainerInterface';

    const DEBUGGER = 'Rougin\Slytherin\Debug\DebuggerInterface';

    const DISPATCHER = 'Rougin\Slytherin\Dispatching\DispatcherInterface';

    const MIDDLEWARE = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

    const REQUEST = 'Psr\Http\Message\ServerRequestInterface';

    const RESPONSE = 'Psr\Http\Message\ResponseInterface';

    const ROUTER = 'Rougin\Slytherin\Dispatching\RouterInterface';

    const TEMPLATE = 'Rougin\Slytherin\Template\RendererInterface';

    /**
     * @var \Rougin\Slytherin\IoC\ContainerInterface
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
     * {@inheritdoc}
     */
    public function add($id, $concrete = null)
    {
        $this->container->add($id, $concrete);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Gets an instance of the container.
     *
     * @return \Rougin\Slytherin\IoC\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Gets the debugger.
     *
     * @return \Rougin\Slytherin\Debugger\DebuggerInterface
     */
    public function getDebugger()
    {
        return $this->get(self::DEBUGGER);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "getContainer" instead.
     *
     * @return \Rougin\Slytherin\IoC\ContainerInterface
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
        return $this->get(self::DISPATCHER);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "getDebugger" instead.
     *
     * @return \Rougin\Slytherin\Debug\DebuggerInterface
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
        return $this->get(self::REQUEST);
    }

    /**
     * Gets the response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHttpResponse()
    {
        return $this->get(self::RESPONSE);
    }

    /**
     * Gets the middleware.
     *
     * @return \Rougin\Slytherin\Middleware\MiddlewareInterface
     */
    public function getMiddleware()
    {
        return $this->get(self::MIDDLEWARE);
    }

    /**
     * Gets the template.
     *
     * @return \Rougin\Slytherin\Template\RendererInterface
     */
    public function getTemplate()
    {
        return $this->get(self::TEMPLATE);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Sets the container.
     *
     * @param \Rougin\Slytherin\IoC\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Sets the debugger.
     *
     * @param  \Rougin\Slytherin\Debugger\DebuggerInterface $debugger
     */
    public function setDebugger(DebuggerInterface $debugger)
    {
        return $this->add(self::DEBUGGER, $debugger);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "setContainer" instead.
     *
     * @param  \Rougin\Slytherin\IoC\ContainerInterface $injector
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
    public function setDispatcher(DispatcherInterface $dispatcher)
    {
        return $this->add(self::DISPATCHER, $dispatcher);
    }

    /**
     * NOTE: To be removed in v1.0.0. Use "setDebugger" instead.
     *
     * @param  \Rougin\Slytherin\Debug\DebuggerInterface $debugger
     * @return self
     */
    public function setErrorHandler(DebuggerInterface $debugger)
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
        $this->add(self::REQUEST, $request);

        return $this->add(self::RESPONSE, $response);
    }

    /**
     * Sets the middleware.
     *
     * @param  \Rougin\Slytherin\Middleware\MiddlewareInterface $middleware
     * @return self
     */
    public function setMiddleware(MiddlewareInterface $middleware)
    {
        return $this->add(self::MIDDLEWARE, $middleware);
    }

    /**
     * Sets the template.
     *
     * @param  \Rougin\Slytherin\Template\RendererInterface $template
     * @return self
     */
    public function setTemplate(RendererInterface $template)
    {
        return $this->add(self::TEMPLATE, $template);
    }
}

<?php

namespace Rougin\Slytherin\Previous;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\ComponentCollection;
use Rougin\Slytherin\Dispatching\Dispatcher;
use Rougin\Slytherin\ErrorHandler\Whoops;
use Rougin\Slytherin\Http\Uri;
use Rougin\Slytherin\IoC\Auryn;
use Rougin\Slytherin\Middleware\StratigilityMiddleware;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\Twig;
use Rougin\Slytherin\Template\TwigLoader;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Stratigility\MiddlewarePipe;

/**
 * @deprecated since ~0.9, legacy builder using deprecated classes (Application, ComponentCollection, Dispatching, IoC, etc.).
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Builder
{
    /**
     * @var \Rougin\Slytherin\ComponentCollection
     */
    protected $collect;

    /**
     * @var string|null
     */
    protected $method = null;

    /**
     * @var string|null
     */
    protected $uri = null;

    /**
     * @return \Rougin\Slytherin\Application
     */
    public function make()
    {
        $this->collect = new ComponentCollection;

        // Initialize the container interface ---
        $this->setContainer();
        // --------------------------------------

        // Initialize the debugger interface ---
        $this->setDebugger();
        // -------------------------------------

        // Initialize the HTTP interfaces ---
        $this->setHttp();
        // ----------------------------------

        // Initialize the routing dispatcher interface ---
        $this->setRouting();
        // -----------------------------------------------

        // Initialize the middleware ---
        $this->setMiddleware();
        // -----------------------------

        return new Application($this->collect);
    }

    /**
     * @param string $method
     * @param string $uri
     *
     * @return \Rougin\Slytherin\Application
     */
    public function set($method, $uri)
    {
        $this->method = $method;

        $this->uri = $uri;

        return $this->make();
    }

    /**
     * @return self
     */
    protected function setContainer()
    {
        // Initialize the RendererInterface ------
        $loader = new TwigLoader;

        $env = $loader->load(__DIR__ . '/Plates');

        $renderer = System::TEMPLATE;
        // ---------------------------------------

        $auryn = new Auryn(new \Auryn\Injector);

        // Create an alias for the RendererInterface ---
        $twig = new Twig($env);

        $auryn->share($twig);

        $auryn->alias($renderer, get_class($twig));
        // ---------------------------------------------

        $this->collect->setDependencyInjector($auryn);

        return $this;
    }

    /**
     * @return self
     */
    protected function setDebugger()
    {
        $whoops = new Whoops;

        $this->collect->setErrorHandler($whoops);

        return $this;
    }

    /**
     * @return self
     */
    protected function setHttp()
    {
        $method = $this->method;

        /** @var \Psr\Http\Message\ServerRequestInterface */
        $request = ServerRequestFactory::fromGlobals();

        $uri = $this->uri;

        if ($method && $uri)
        {
            $uri = new Uri('http://localhost:8000' . $uri);

            $request = $request->withUri($uri);

            $request = $request->withMethod($method);
        }

        $response = new Response;

        $this->collect->setHttp($request, $response);

        return $this;
    }

    /**
     * @return self
     */
    protected function setMiddleware()
    {
        $pipe = new MiddlewarePipe;

        $middleware = new StratigilityMiddleware($pipe);

        $this->collect->setMiddleware($middleware);

        return $this;
    }

    /**
     * @return self
     */
    protected function setRouting()
    {
        /** @var \Rougin\Slytherin\Routing\RouterInterface */
        $router = require __DIR__ . '/Router.php';

        $dispatcher = new Dispatcher($router);

        $this->collect->setDispatcher($dispatcher);

        return $this;
    }
}

<?php

namespace Rougin\Slytherin\Previous;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\ComponentCollection;
use Rougin\Slytherin\Dispatching\Dispatcher;
use Rougin\Slytherin\ErrorHandler\Whoops;
use Rougin\Slytherin\Http\Uri;
use Rougin\Slytherin\IoC\Auryn;
use Rougin\Slytherin\Middleware\StratigilityMiddleware;
use Rougin\Slytherin\Template\Twig;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Stratigility\MiddlewarePipe;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Builder
{
    /**
     * @var \Rougin\Slytherin\ComponentCollection
     */
    protected $collection;

    public function __construct()
    {
        $this->collection = new ComponentCollection;
    }

    /**
     * @param  string $method
     * @param  string $uri
     * @return \Rougin\Slytherin\Application
     */
    public function make($method = null, $uri = null)
    {
        // Initialize the DependencyInjectorInterface ---
        $this->setContainer();
        // ----------------------------------------------

        // Initialize the ErrorHandlerInterface ---
        $this->setDebugger();
        // ----------------------------------------

        // Initialize the ServerRequestInterface and ResponseInterface ---
        $this->setHttp($method, $uri);
        // ---------------------------------------------------------------

        // Initialize the routing dispatcher interface -----
        $this->setRouting();
        // -------------------------------------------------

        // Initialize the middleware ---
        $this->setMiddleware();
        // -----------------------------

        return new Application($this->collection);
    }

    /**
     * @return self
     */
    protected function setContainer()
    {
        // Initialize the RendererInterface ----------------------
        $views = (string) realpath(__DIR__ . '/Plates');
        $loader = new Twig_Loader_Filesystem($views);
        $twig = new Twig(new Twig_Environment($loader));
        $renderer = 'Rougin\Slytherin\Template\RendererInterface';
        // -------------------------------------------------------

        $auryn = new Auryn(new \Auryn\Injector);

        // Create an alias for the RendererInterface ---
        $auryn->share($twig);
        $auryn->alias($renderer, get_class($twig));
        // ---------------------------------------------

        $this->collection->setDependencyInjector($auryn);

        return $this;
    }

    /**
     * @return self
     */
    protected function setDebugger()
    {
        $whoops = new Whoops;

        $this->collection->setErrorHandler($whoops);

        return $this;
    }

    /**
     * @param  string $method
     * @param  string $uri
     * @return self
     */
    protected function setHttp($method = null, $uri = null)
    {
        $request = ServerRequestFactory::fromGlobals();

        if ($method && $uri)
        {
            $uri = new Uri('http://localhost:8000' . $uri);

            $request = $request->withUri($uri);

            $request = $request->withMethod($method);
        }

        $response = new Response;

        $this->collection->setHttp($request, $response);

        return $this;
    }

    /**
     * @return self
     */
    protected function setMiddleware()
    {
        $pipe = new MiddlewarePipe;

        $middleware = new StratigilityMiddleware($pipe);

        $this->collection->setMiddleware($middleware);

        return $this;
    }

    /**
     * @return self
     */
    protected function setRouting()
    {
        $router = __DIR__ . '/Router.php';

        $router = require realpath((string) $router);

        $dispatcher = new Dispatcher($router);

        $this->collection->setDispatcher($dispatcher);

        return $this;
    }
}

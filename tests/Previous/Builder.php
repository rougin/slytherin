<?php

namespace Rougin\Slytherin\Previous;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\ComponentCollection;
use Rougin\Slytherin\Dispatching\Dispatcher;
use Rougin\Slytherin\ErrorHandler\Whoops;
use Rougin\Slytherin\Http\Uri;
use Rougin\Slytherin\IoC\Auryn;
use Rougin\Slytherin\Middleware\StratigilityMiddleware;
use Rougin\Slytherin\Template\RendererInterface;
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

    protected function setDebugger()
    {
        $whoops = new Whoops(new \Whoops\Run);

        $this->collection->setErrorHandler($whoops);

        return $this;
    }

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

    protected function setMiddleware()
    {
        $pipe = new MiddlewarePipe;

        $middleware = new StratigilityMiddleware($pipe);

        $this->collection->setMiddleware($middleware);

        return $this;
    }

    protected function setRouting()
    {
        $router = require realpath(__DIR__ . '/Router.php');

        $response = $this->collection->getHttpResponse();

        $dispatcher = new Dispatcher($router, $response);

        $this->collection->setDispatcher($dispatcher);

        return $this;
    }
}

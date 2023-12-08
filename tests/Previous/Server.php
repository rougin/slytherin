<?php

use Rougin\Slytherin\Application;
use Rougin\Slytherin\ComponentCollection;
use Rougin\Slytherin\Dispatching\Dispatcher;
use Rougin\Slytherin\ErrorHandler\Whoops;
use Rougin\Slytherin\IoC\Auryn;
use Rougin\Slytherin\Middleware\StratigilityMiddleware;
use Rougin\Slytherin\Template\RendererInterface;
use Rougin\Slytherin\Template\Twig;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Stratigility\MiddlewarePipe;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$component = new ComponentCollection;

// Initialize the RendererInterface ------------
$views = (string) realpath(__DIR__ . '/Plates');
$loader = new Twig_Loader_Filesystem($views);
$twig = new Twig(new Twig_Environment($loader));
$renderer = RendererInterface::class;
// ---------------------------------------------

// Initialize the DependencyInjectorInterface ---
$auryn = new Auryn(new \Auryn\Injector);
// Create an alias for the RendererInterface ---
$auryn->share($twig);
$auryn->alias($renderer, get_class($twig));
// ---------------------------------------------
$component->setDependencyInjector($auryn);
// ----------------------------------------------

// Initialize the ErrorHandlerInterface ---
$whoops = new Whoops(new \Whoops\Run);
$component->setErrorHandler($whoops);
// ----------------------------------------

// Initialize the ServerRequestInterface and ResponseInterface ---
$request = ServerRequestFactory::fromGlobals();
$response = new Response;
$component->setHttp($request, $response);
// ---------------------------------------------------------------

// Initialize the routing dispatcher interface -----
$router = require realpath(__DIR__ . '/Router.php');
$dispatcher = new Dispatcher($router, $response);
$component->setDispatcher($dispatcher);
// -------------------------------------------------

// Initialize the middleware -------------------
$pipe = new MiddlewarePipe;
$middleware = new StratigilityMiddleware($pipe);
$component->setMiddleware($middleware);
// ---------------------------------------------

// Initialize then run the Application ---
$app = new Application($component);

$app->run();
// ---------------------------------------

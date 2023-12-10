# Erratum

The following are the erratum for each `README.md` found from the previous versions:

## 0.9.0

This version introduced a support for PSR-15 implementations based on `http-interop/http-middleware`. With this, kindly add the said package and update the packages using `composer update`:

``` diff
 {
     "require":
     {
         "filp/whoops": "~1.0",
+        "http-interop/http-middleware": "0.4.1",
         "nikic/fast-route": "~1.0",
         "rdlowrey/auryn": "~1.0",
-        "rougin/slytherin": "~0.8.0",
+        "rougin/slytherin": "~0.9.0",
         "twig/twig": "~1.0",
         "zendframework/zend-diactoros": "~1.0",
         "zendframework/zend-stratigility": "~1.0"
     }
 }
```

## 0.7.0

### Handling of `Components`

One of the features in this version is the enhanced handling of `Components`. Instead of manually defining dependencies to core interfaces of Slytherin (e.g., `Routing`, `Debugger`, `Http`, `Middleware`), it is now possible to create a class around it and add the said class to a `Collector`:

``` php
// src/Packages/ContainerPackage.php

namespace Rougin\Nostalgia\Packages;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\IoC\Auryn;
use Rougin\Slytherin\Template\RendererInterface;
use Rougin\Slytherin\Template\Twig;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ContainerPackage extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     * 
     * @var string
     */
    protected $type = 'container';

    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    public function get()
    {
        $root = realpath(dirname(dirname(__DIR__)));

        // Initialize the RendererInterface -------------
        $views = (string) realpath($root . '/app/views');
        $loader = new Twig_Loader_Filesystem($views);
        $twig = new Twig(new Twig_Environment($loader));
        $renderer = RendererInterface::class;
        // ----------------------------------------------

        // Initialize the DependencyInjectorInterface ---
        $auryn = new Auryn(new \Auryn\Injector);
        // Create an alias for the RendererInterface ---
        $auryn->share($twig);
        $auryn->alias($renderer, get_class($twig));
        // ---------------------------------------------

        return $auryn;
    }
}
```

``` php
// src/Packages/DebuggerPackage.php

namespace Rougin\Nostalgia\Packages;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\ErrorHandler\Whoops;

class DebuggerPackage extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     * 
     * @var string
     */
    protected $type = 'debugger';

    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    public function get()
    {
        return new Whoops(new \Whoops\Run);
    }
}
```

``` php
// src/Packages/HttpPackage.php

namespace Rougin\Nostalgia\Packages;

use Rougin\Slytherin\Component\AbstractComponent;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class HttpPackage extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     * 
     * @var string
     */
    protected $type = 'http';

    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    public function get()
    {
        $request = ServerRequestFactory::fromGlobals();

        return array($request, new Response);
    }
}
```

``` php
// src/Packages/MiddlewarePackage.php

namespace Rougin\Nostalgia\Packages;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Middleware\StratigilityMiddleware;
use Zend\Stratigility\MiddlewarePipe;

class MiddlewarePackage extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     * 
     * @var string
     */
    protected $type = 'middleware';

    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    public function get()
    {
        return new StratigilityMiddleware(new MiddlewarePipe);
    }
}
```

``` php
// src/Packages/TemplatePackage.php

namespace Rougin\Nostalgia\Packages;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Template\Twig;
use Twig_Environment;
use Twig_Loader_Filesystem;

class TemplatePackage extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     * 
     * @var string
     */
    protected $type = 'template';

    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    public function get()
    {
        $root = realpath(dirname(dirname(__DIR__)));

        $views = (string) realpath($root . '/app/views');

        $loader = new Twig_Loader_Filesystem($views);

        return new Twig(new Twig_Environment($loader));
    }
}
```

After creating the classes that extends on `AbstractComponent`, update the following code below to `app/web/index.php`:

``` diff
// app/web/index.php

+use Rougin\Nostalgia\Packages\ContainerPackage;
+use Rougin\Nostalgia\Packages\DebuggerPackage;
+use Rougin\Nostalgia\Packages\HttpPackage;
+use Rougin\Nostalgia\Packages\MiddlewarePackage;
+use Rougin\Nostalgia\Packages\RoutingPackage;
+use Rougin\Nostalgia\Packages\TemplatePackage;
 use Rougin\Slytherin\Application;
-use Rougin\Slytherin\ComponentCollection;
-use Rougin\Slytherin\Dispatching\Dispatcher;
-use Rougin\Slytherin\ErrorHandler\Whoops;
-use Rougin\Slytherin\IoC\Auryn;
-use Rougin\Slytherin\Middleware\StratigilityMiddleware;
-use Rougin\Slytherin\Template\RendererInterface;
-use Rougin\Slytherin\Template\Twig;
-use Zend\Diactoros\Response;
-use Zend\Diactoros\ServerRequestFactory;
-use Zend\Stratigility\MiddlewarePipe;
+use Rougin\Slytherin\Component\Collector;

 $root = dirname(dirname(__DIR__));

 require $root . '/vendor/autoload.php';

-$component = new ComponentCollection;
-
-// Initialize the RendererInterface -------------
-$views = (string) realpath($root . '/app/views');
-$loader = new Twig_Loader_Filesystem($views);
-$twig = new Twig(new Twig_Environment($loader));
-$renderer = RendererInterface::class;
-// ----------------------------------------------
+$components = array();

 // Initialize the DependencyInjectorInterface ---
-$auryn = new Auryn(new \Auryn\Injector);
-// Create an alias for the RendererInterface ---
-$auryn->share($twig);
-$auryn->alias($renderer, get_class($twig));
-// ---------------------------------------------
-$component->setDependencyInjector($auryn);
+$components[] = new ContainerPackage;
 // ----------------------------------------------

 // Initialize the ErrorHandlerInterface ---
-$whoops = new Whoops(new \Whoops\Run);
-$component->setErrorHandler($whoops);
+$components[] = new DebuggerPackage;
 // ----------------------------------------

 // Initialize the ServerRequestInterface and ResponseInterface ---
-$request = ServerRequestFactory::fromGlobals();
-$response = new Response;
-$component->setHttp($request, $response);
+$components[] = new HttpPackage;
 // ---------------------------------------------------------------

 // Initialize the routing dispatcher interface ---
-$router = require "$root/app/config/routes.php";
-$dispatcher = new Dispatcher($router, $response);
-$component->setDispatcher($dispatcher);
+$components[] = new RoutingPackage;
 // -----------------------------------------------

 // Initialize the middleware -------------------
-$pipe = new MiddlewarePipe;
-$middleware = new StratigilityMiddleware($pipe);
-$component->setMiddleware($middleware);
+$components[] = new MiddlewarePackage;
 // ---------------------------------------------

+// Initialize the template renderer ------------
+$components[] = new TemplatePackage;
+// ---------------------------------------------

 // Initialize then run the Application ---
-$app = new Application($component);
+$collection = Collector::get($components);

-$app->run();
+(new Application($collection))->run();
 // ---------------------------------------
```

## 0.5.0

### Middlewares

Middlewares in concept are a layer of actions or callables that are wrapped around a piece of core logic in an application. To add middlewares in Slytherin, kindly install `Stratigility` first for handling the middlewares:

``` diff
 {
     "require":
     {
         "rougin/slytherin": "~0.5.0",
         "filp/whoops": "~1.0",
         "nikic/fast-route": "~1.0",
         "rdlowrey/auryn": "~1.0",
         "twig/twig": "~1.0",
-        "zendframework/zend-diactoros": "~1.0"
+        "zendframework/zend-diactoros": "~1.0",
+        "zendframework/zend-stratigility": "~1.0"
 }
```

``` bash
$ composer update
```

After installing the said package, update the code below to support the handling of middlewares:

``` php
// app/web/index.php

// ...

// Initialize the middleware -------------------
$pipe = new MiddlewarePipe;
$middleware = new StratigilityMiddleware($pipe);
$component->setMiddleware($middleware);
// ---------------------------------------------

$app = new Application($component);

// ...
```

``` php
// app/config/routes.php

$router = new Rougin\Slytherin\Dispatching\Router;

// ...

// Add the middlewares to a specified route ---------------
$items = array('Rougin\Nostalgia\Handlers\Hello');

$router->addRoute('GET', '/hello', function () {}, $items);
// --------------------------------------------------------

// ...
```

``` php
// src/Handlers/Hello.php

namespace Rougin\Nostalgia\Handlers;

/**
 * This is a sample middleware
 */
class Hello
{
    /**
     * Creating middlewares should follow this __invoke method.
     */
    public function __invoke($request, $response, $next = null)
    {
        $response = $next($request, $response);

        $response->getBody()->write('Hello from middleware');

        return $response;
    }
}
```

**NOTE**: Due to the nature of middleware and as a new concept, integrating middlewares to existing routes is not yet supported.

## 0.4.0

In this version, the `patricklouys/http` has been removed in favor for PSR-07 (`psr/http-message`). With this, kindly add a package that is compliant to PSR-07 (e.g., `zendframework/zend-diactoros`) in the `composer.json`:

``` diff
 {
     "require":
     {
         "filp/whoops": "~2.0",
         "nikic/fast-route": "~1.0",
-        "patricklouys/http": "~1.0",
         "rdlowrey/auryn": "~1.0",
-        "rougin/slytherin": "~0.3.0",
-        "twig/twig": "~1.0"
+        "rougin/slytherin": "~0.4.0",
+        "twig/twig": "~1.0",
+        "zendframework/zend-diactoros": "~1.0"
     }
 }
```

The version also favors the PSR-11 (`container-interop/container-interop`). The classes that implements `DependencyInjectorInterface` must be migrated to `ContainerInterface`:

``` php
namespace Interop\Container;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
interface ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id);

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id);
}
```

Perform `composer update` afterwards to update the specified packages.

## 0.3.0

### Usage

As per documentation, implementing interfaces are required to use Slytherin components. However in this version, the implemented third-party packages are not included (e.g., `patricklouys/http`, etc.) and needs to be installed manually. Kindly include the said packages in the `composer.json`:

``` diff
 {
     "require":
     {
+        "filp/whoops": "~1.0",
+        "nikic/fast-route": "~1.0",
+        "patricklouys/http": "~1.0",
+        "rdlowrey/auryn": "~1.0",
-        "rougin/slytherin": "~0.3.0"
+        "rougin/slytherin": "~0.3.0",
+        "twig/twig": "~1.0"
     }
 }
```
# Upgrade Guide

Below are the guides when upgrading from specified versions due to backward compatibility breaks:

> [!NOTE]
> There should be no backward compatibility issues if using the public methods of Slytherin components that are documented starting from `v0.3.0`. The following below should be considered if there are custom implementations that uses Slytherin's interfaces.

## From `v0.8.0` to `v0.9.0`

### Change of `DebuggerInterface` to `ErrorHandlerInterface`

The `DebuggerInterface` is renamed to `ErrorHandlerInterface`. Although there is no backward compatibility break in this change, the specified interface removes required methods and may not be used in Slytherin:

``` diff
namespace Rougin\Slytherin\Debug;

-interface DebuggerInterface
+interface ErrorHandlerInterface
 {
-    /**
-     * Sets up the environment to be used.
-     *
-     * @param  string $environment
-     * @return void
-     */
-    public function setEnvironment($environment);
-
-    /**
-     * Returns the specified environment.
-     *
-     * @return string
-     */
-    public function getEnvironment();

     /**
      * Registers the instance as a debugger.
      * 
      * @return object
      */
     public function display();
 }
```

### Transition to PSR-15 (HTTP middlewares)

Due to the transition to PSR-15, the `Middleware` has been reworked. The `MiddlewareInterface` has been changed and it should be compatible with the various implementations of PSR-15:

``` diff
 namespace Rougin\Slytherin\Middleware;

-use Psr\Http\Message\ResponseInterface as Response;
-use Psr\Http\Message\ServerRequestInterface as Request;
+use Psr\Http\Message\ServerRequestInterface;

 interface MiddlewareInterface
 {
     /**
-     * @param  \Psr\Http\Message\RequestInterface  $request
-     * @param  \Psr\Http\Message\ResponseInterface $response
-     * @param  array                               $queue
-     * @return \Psr\Http\Message\ResponseInterface|null
+     * @param  \Psr\Http\Message\ServerRequestInterface      $request
+     * @param  \Rougin\Slytherin\Middleware\HandlerInterface $handler
+     * @return \Psr\Http\Message\ResponseInterface
      */
-    public function __invoke(Request $request, Response $response, array $queue = []);
+    public function process(ServerRequestInterface $request, HandlerInterface $handler);
 }
```

### Additional method for `DispatcherInterface` in `Routing`

The `DispatcherInterface` for `Routing` has a new method named `setRouter`. If using the specified interface, kindly implement the recent method:

``` diff
 namespace Rougin\Slytherin\Routing;

 /**
  * Dispatcher Interface
  *
  * An interface for handling third-party route dispatchers.
  *
  * @package Slytherin
  * @author  Rougin Gutib <rougingutib@gmail.com>
  */
 interface DispatcherInterface
 {
     /**
      * Dispatches against the provided HTTP method verb and URI.
      *
      * @param  string $method
      * @param  string $uri
      * @return \Rougin\Slytherin\Routing\RouteInterface
      *
      * @throws \BadMethodCallException
      */
     public function dispatch($method, $uri);

+    /**
+     * Sets the router and parse its available routes if needed.
+     *
+     * @param  \Rougin\Slytherin\Routing\RouterInterface $router
+     * @return self
+     *
+     * @throws \UnexpectedValueException
+     */
+    public function setRouter(RouterInterface $router);
 }
```

## From `v0.7.0` to `v0.8.0`

No known backward compatibility issues found.

## From `v0.6.0` to `v0.7.0`

### Upgrade version of `filp/whoops`

Although no backward compatibility issues found in Slytherin's code, one of the Slytherin's supported packages, `filp/whoops`, has an issue regarding PHP errors. With this, kindly change its version to `~2.0` in the `composer.json` then perform `composer update` after:

``` diff
 {
     "require":
     {
-        "filp/whoops": "~1.0",
+        "filp/whoops": "~2.0",
     }
 }
```

See the [Issue #341](https://github.com/filp/whoops/issues/341) from the `filp/whoops` repository for reference.

## From `v0.5.0` to `v0.6.0`

No known backward compatibility issues found.

## From `v0.4.0` to `v0.5.0`

No known backward compatibility issues found.

## From `v0.3.0` to `v0.4.0`

### Transition to PSR-07 (HTTP messages) and PSR-11 (Containers)

The `v0.4.0` version requires a PSR-07 and PSR-11 compliant packages. See the `v0.4.0` in `ERRATUM` for updating the `composer.json`.

With the transition to PSR-07, kindly update the following classes from `index.php`:

``` diff
 use Rougin\Slytherin\Application;
 use Rougin\Slytherin\ComponentCollection;
 use Rougin\Slytherin\Dispatching\Dispatcher;
 use Rougin\Slytherin\ErrorHandler\Whoops;
-use Rougin\Slytherin\Http\Request;
-use Rougin\Slytherin\Http\Response;
 use Rougin\Slytherin\IoC\Auryn;
 use Rougin\Slytherin\Template\RendererInterface;
 use Rougin\Slytherin\Template\Twig;
+use Zend\Diactoros\Response;
+use Zend\Diactoros\ServerRequestFactory;

 // ...

// Initialize the RequestInterface and ResponseInterface -----------------------------
-$stream = file_get_contents('php://input');
-$request = new \Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, $stream);
-$request = new Request($request);
-$response = new Response(new \Http\HttpResponse);
+$request = ServerRequestFactory::fromGlobals();
+$response = new Response;
 $component->setHttp($request, $response);
// -----------------------------------------------------------------------------------

 // ...
```

### Change from `ErrorHandler` to `Debug`

The `ErrorHandlerInterface` has been moved from `ErrorHandler` to `Debug` and renamed as `DebuggerInterface`. No backward compatibility break should occur as the latter interface extends to the former:

``` diff
-namespace Rougin\Slytherin\ErrorHandler;
+namespace Rougin\Slytherin\Debug;

 /**
- * Error Handler Interface
+ * Debugger Interface
  *
- * An interface for handling third party error handlers
+ * An interface for handling third party debuggers.
  *
  * @package Slytherin
  * @author  Rougin Royce Gutib <rougingutib@gmail.com>
  */
-interface ErrorHandlerInterface
+interface DebuggerInterface
```

## From `v0.2.0` to `v0.3.0`

### Migrating to a micro-framework package

Due to transition from an application project to a micro-framework package, the following updates must be performed:

Update the following details in `composer.json`:

``` diff
 {
     "require":
     {
-        "rougin/slytherin": "~0.2.0"
+        "filp/whoops": "~2.0",
+        "nikic/fast-route": "~1.0",
+        "patricklouys/http": "~1.0",
+        "rdlowrey/auryn": "~1.0",
+        "rougin/slytherin": "~0.3.0",
+        "twig/twig": "~1.0"
     },
     "autoload":
     {
         "psr-4":
         {
-            "Controllers\\": "app/controllers",
-            "Libraries\\": "app/libraries",
-            "Models\\": "app/models"
+            "Rougin\\Nostalgia\\": "src"
         }
-    },
-    "scripts":
-    {
-        "post-update-cmd":
-        [
-            "Rougin\\Slytherin\\Installer::deploy"
-        ]
     }
 }
```

> [!NOTE]
> `Rougin\\Nostalgia\\` is only an example for the namespace. The `src` directory should have its own namespace for easy importing of its classes.

Then execute `composer update` to update the packages:

``` bash
$ composer update
```

After updating the packages, copy the `index.php` to `app/web` directory:

``` diff
+app/
+├─ web/
+│  ├─ index.php
-index.php
```

Then copy the following code below to the said `index.php`:

``` php
use Rougin\Slytherin\Application;
use Rougin\Slytherin\ComponentCollection;
use Rougin\Slytherin\Dispatching\Dispatcher;
use Rougin\Slytherin\ErrorHandler\Whoops;
use Rougin\Slytherin\Http\Request;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\IoC\Auryn;
use Rougin\Slytherin\Template\RendererInterface;
use Rougin\Slytherin\Template\Twig;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$component = new ComponentCollection;

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
$component->setDependencyInjector($auryn);
// ----------------------------------------------

// Initialize the ErrorHandlerInterface ---
$whoops = new Whoops(new \Whoops\Run);
$component->setErrorHandler($whoops);
// ----------------------------------------

// Initialize the RequestInterface and ResponseInterface -----------------------------
$stream = file_get_contents('php://input');
$request = new \Http\HttpRequest($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER, $stream);
$request = new Request($request);
$response = new Response(new \Http\HttpResponse);
$component->setHttp($request, $response);
// -----------------------------------------------------------------------------------

// Initialize the routing dispatcher interface ---
$router = require "$root/app/config/routes.php";
$dispatcher = new Dispatcher($router, $response);
$component->setDispatcher($dispatcher);
// -----------------------------------------------

// Initialize then run the Application ---
$app = new Application($component);

$app->run();
// ---------------------------------------
```

From the `app/config/routes.php` file, change the syntax with the following below:

``` php
use Rougin\Slytherin\Dispatching\Router;

$name = 'Rougin\Nostalgia\Routes';

$router = new Router;

$router->addRoute('GET', '/', [ "$name\Welcome", 'index' ]);

$router->addRoute('GET', '/users', [ "$name\Users", 'index' ]);

return $router;
```

Create a `src` directory then copy `controllers`, `libraries`, and `models` to `src/Routes`, `src/Packages`, and `src/Models` respectively:

``` diff
 app/
-├─ controllers/
-├─ packages/
-├─ models/
+src/
+├─ Models/
+├─ Packages/
+├─ Routes/
```

Once copied, remove the `extends Controller` in each of the files in the `src/Routes` directory. If the route uses `View`, replace it with `RendererInterface`:

``` diff
-namespace Controllers;
+namespace Rougin\Nostalgia\Routes;

-use Rougin\Slytherin\Controller;
-use Rougin\Slytherin\View;
+use Rougin\Slytherin\Template\RendererInterface;

-class Welcome extends Controller
+class Welcome
 {
+       protected $renderer;
+
+       public function __construct(RendererInterface $renderer)
+       {
+               $this->renderer = $renderer;
+       }
+
        public function index()
        {
-               return View::render('welcome/index');
+               return $this->renderer->render('welcome/index');
        }
 }
```

If using the `View` class for handling templates, rename the files in the `view` directory with `.html`:

``` diff
 app/
 ├─ views/
 │  ├─ users/
+│  │  ├─ index.html
-│  │  ├─ index.php
 │  ├─ welcome/
+│  │  ├─ index.html
-│  │  ├─ index.php
```

If using the `Model` class for handling database results, replace it with the implementation of `PDO`:

``` diff
-namespace Models;
+namespace Rougin\Nostalgia\Models;

-use Rougin\Slytherin\Model;
-
-class User extends Model
+class User
 {
-    public function get($page = 1, $limit = 10)
+    protected $pdo;
+
+    public function __construct()
     {
-        $pdo = $this->databaseHandle;
+        $items = require __DIR__ . '/../../app/config/databases.php';
+
+        $data = $items['default'];
+
+        $dsn = "{$data['driver']}:host={$data['hostname']};dbname={$data['database']}";
+
+        $pdo = new \PDO($dsn, $data['username'], $data['password']);

+        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
+
+        $this->pdo = $pdo;
+    }
+
+    public function get($page = 1, $limit = 10)
+    {
         $query = 'SELECT * FROM users';

         $offset = ($page - 1) * $limit;

         $query .= " LIMIT $limit OFFSET $offset";

-        $st = $pdo->prepare($query);
+        $st = $this->pdo->prepare($query);

         $st->execute();
```

> [!NOTE]
> Use a third-party `PDO` implementation for improving code maintainability and readability.
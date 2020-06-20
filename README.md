# Slytherin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Slytherin is a simple and extensible PHP micro-framework that tries to achieve a SOLID-based design for creating your next web application. It uses [Composer](https://getcomposer.org/) as the dependency package manager to add, update or even remove external packages.

## Installation

Install `Slytherin` through [Composer](https://getcomposer.org/):

``` bash
$ composer require rougin/slytherin
```

## Basic Usage

Slytherin can be implemented in either the `ContainerInterface`, `IntegrationInterface` or a mixed of both:

### Using the `ContainerInterface`

``` php
use App\Http\Controllers\WelcomeController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Application;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\Routing\DispatcherInterface;
use Rougin\Slytherin\Routing\Router;

// Define HTTP objects that is compliant to PSR-07 standards
$request = new ServerRequest((array) $_SERVER);

$response = new Response(http_response_code());

$router = new Router;

// Create a new route from WelcomeController class...
$router->get('/', WelcomeController::class . '@index');

// ...then define it to a dispatcher
$dispatcher = new Dispatcher($router);

// Add the above objects through a container
$container = new Container;

// Set the request as the PSR-07 server request instance
$container->set(ServerRequestInterface::class, $request);

// Set the response as the PSR-07 response instance
$container->set(ResponseInterface::class, $response);

// Set the dispatcher in the Routing\DispatcherInterface
$container->set(DispatcherInterface::class, $dispatcher);

// Lastly, run the application
(new Application($container))->run();
```

### Using the `IntegrationInterface`

``` php
use App\Http\Controllers\WelcomeController;
use Rougin\Slytherin\Application;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Http\HttpIntegration;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Routing\RoutingIntegration;

// Specify the integrations to be included and defined
$integrations = array(HttpIntegration::class);

$integrations[] = RoutingIntegration::class;

$router = new Router;

// Create a new route from the WelcomeController class
$router->get('/', WelcomeController::class . '@index');

// Supply values to integrations using a configuration file
$config = (new Configuration)->set('app.router', $router);

$config->set('app.http.server', (array) $_SERVER);

// Use the integrations and run the application
$app = new Application(new Container, $config);

$app->integrate((array) $integrations)->run();
```

### Running the application using PHP's built-in web server:

``` bash
$ php -S localhost:8000 index.php
```

Now open a web browser and access to [http://localhost:8000](http://localhost:8000).

Regarding the example implementation above, a chosen package must be implement with a provided interface in order for it to be integrated in Slytherin. More information about this can be found in the **Using Interfaces** section.

### Packages

Slytherin also provide implementations on each component (container, dispatcher, etc.) that are built on top of other existing third-party packages. Kindly install their own respective dependencies first before using it directly. These packages can be located at the [src](https://github.com/rougin/slytherin/tree/master/src) directory.

## Using Interfaces

The following items below are needed to run a Slytherin application:

* Container
  * Stores the instances and dependences of the application for later use
  * Package must be implemented in a [PSR-11](https://www.php-fig.org/psr/psr-11/) standard
* Routing
    * Dispatches defined routes to different handlers depending on rules that you have set up
    * Package must be implemented in a `DispatcherInterface`
* Http
    * Provides a nice object-oriented interface for handling HTTP variables
    * Package must be implemented in a [PSR-07](https://www.php-fig.org/psr/psr-7/) standard

The following items below are not required but might be useful in the application development:

* Error Handler
    * Detects and displays the error messages in a nice way
    * Package must be implemented in an `ErrorHandlerInterface`
* Middleware
    * Enables you to manipulate HTTP Request and HTTP Response objects with ease
    * Package must be implemented in [PSR-07](https://www.php-fig.org/psr/psr-7/) and [PSR-15](https://www.php-fig.org/psr/psr-15/) standards
* Template
    * Even PHP has already a built-in template engine, it is much easier to separate the concerns between the data and the template
    * Package must be implemented in a `RendererInterface`

Including a package requires to use an [interface](http://php.net/manual/en/language.oop5.interfaces.php) to interact to the framework.

## Integrating Packages

Integrating packages in Slytherin is simple, as long as the usage of interfaces that is introduced in the **Using Interfaces** section was understood.

Before proceeding on how to integrate packages, it is important to have a [PSR-11](https://www.php-fig.org/psr/psr-11/) compliant container in order to resolve and store dependencies of a package.

### Example

[Twig](https://twig.symfony.com/) is a flexible and secure template engine. To integrate it to Slytherin, the `RendererInterface` must be implemented to the said package. An example of this implementation can be found [here](https://github.com/rougin/slytherin/blob/master/src/Template/TwigRenderer.php).

Notice that the `Twig_Environment` is used as a dependency but according to the [documentation](https://twig.symfony.com/doc/2.x/intro.html#installation), it is needed to be prepared first before it will be called as a dependency. In order to use the package globally in the whole application, a container must be used as mentioned earlier.

## Components

### Container

A [container](https://en.wikipedia.org/wiki/Container_(abstract_data_type)) stores defined class objects that can be used later.

``` php
// Foo.php

class Foo
{
    public function baz()
    {
        // ...
    }
}

// Bar.php

class Bar
{
    protected $foo;

    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }

    public function booz()
    {
        return $this->foo->baz();
    }
}
```

This is the implementation for `Bar` to have an instance of `Foo`:

``` php
$foo = new Foo;

$bar = new Bar($foo);
```

To access the said class, it needs to be implemented again which is cumbersome to a developer. The solution for this is to store it into a container so it can be accessed anywhere.

``` php
use Rougin\Slytherin\Container\Container;

// ... Given that Foo and Bar classes were included

$container = new Container;

$container->set('Bar', $bar);

// Returns an instance of Bar
$new = $container->get('Bar');
```

The implementation above covers the basic functionality of a container. Some packages also provides additional functionalities like resolving dependencies and more. A list of packages that implement the dependency injection design pattern at can be seen at the [awesome-php](https://github.com/ziadoz/awesome-php#dependency-injection) repository.

#### Example

To integrate a container to Slytherin, it must be implemented in the [PSR-11](https://www.php-fig.org/psr/psr-11/) standard. An example of this implementation can be found here.

``` php
$container = new Rougin\Slytherin\Container\Container;

// Define your classes and dependencies here...

(new Rougin\Slytherin\Application($container))->run();
```

### Error Handler

An error handler assists a developer in the detection and correction of errors in applications.

Errors are a good way to display what is wrong in an application, from a developer's perspective. However, it can be very dangerous to show a very detailed error message into production because it might get used as a information by hackers on how to gain access to the application. With this kind of vulnerability, error handling must be disabled in a production environment.

#### Example

[Whoops](http://filp.github.io/whoops/) is an error handler package. To integrate it to Slytherin, create a class and implement it to an `ErrorHandlerInterface`. An example of this implementation can be found [here](https://github.com/rougin/slytherin/blob/master/src/Debug/WhoopsErrorHandler.php).

``` php
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Debug\WhoopsErrorHandler;

$container = new Rougin\Slytherin\Container\Container;

$whoops = new WhoopsErrorHandler(new Whoops\Run);

$container->set(ErrorHandlerInterface::class, $whoops);
```

### Routing

The `Routing` component consists of a `Dispatcher` and a `Router`.

[What is the difference between a Router and Dispatcher?](https://stackoverflow.com/questions/11700603/what-is-the-difference-between-url-router-and-dispatcher)

> A `Dispatcher` uses the information from the `Router` to actually generate the resource. If the `Router` is asking for directions then the dispatcher is the actual process of following those directions. the dispatcher knows exactly what to create and the steps needed to generate the resource, but only after getting the directions from the `Router`. Basically, without the `Router`, the `Dispatcher` would not know what are the available routes that can be generated from.

#### Example

To integrate a route dispatcher to Slytherin, a package must be implemented both in `DispatcherInterface` and `RouterInterface` of the Routing component. An example implementation of both [Dispatcher](https://github.com/rougin/slytherin/tree/master/src/Routing/Dispatcher.php) and [Router](https://github.com/rougin/slytherin/tree/master/src/Routing/Router.php) are also provided by the framework.

``` php
use App\Http\Controllers\WelcomeController;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Routing\Dispatcher;
use Rougin\Slytherin\Routing\DispatcherInterface;
use Rougin\Slytherin\Routing\Router;

$router = new Router;

// Create a new route from WelcomeController class...
$router->get('/', WelcomeController::class . '@index');

// ...then define it to a dispatcher
$dispatcher = new Dispatcher($router);

$container = new Container;

$container->set(DispatcherInterface::class, $dispatcher);
```

To dispatch a route, specify the HTTP method and the URI segment to the route dispatcher.

``` php
$dispatcher = $container->get(DispatcherInterface::class);

// Returns the result from WelcomeController::index
$dispatcher->dispatch('GET', '/');
```

### HTTP

In PHP, there are built-in functions that can work in HTTP. The list of functions that are related to it can be found [here](http://php.net/manual/en/ref.http.php). However, a HTTP package with a nice object-oriented interface is greatly recommended.

To integrate a HTTP component to Slytherin, a package must implement both `ServerRequestInterface` and `ResponseInterface` interfaces as defined in [PSR-07](https://www.php-fig.org/psr/psr-7/) standard. A meta document about the implementation can be found [here](https://www.php-fig.org/psr/psr-7/).

``` php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Http\Response;
use Rougin\Slytherin\Http\ServerRequest;

$request = new ServerRequest($_SERVER, $_COOKIE);

$container = new Container;

$container->set(ServerRequestInterface::class, $request);

$response = new Response(http_response_code());

$container->set(ResponseInterface::class, $response);
```

Why need to implement two classes for a HTTP component if it can be in a one cool class? They are needed to be separated because of the [Single Responsibility Principle](https://en.wikipedia.org/wiki/Single_responsibility_principle).

> In object-oriented programming, the said principle states that every class should have responsibility over a single part of the functionality provided by the software, and that responsibility should be entirely encapsulated by the class. All its services should be narrowly aligned with that responsibility. With that principle, it's easier for you to debug a certain problem in a class because you can easily determine the responsibility that is included to it.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer require filp/whoops league/container nikic/fast-route phroute/phroute rdlowrey/auryn twig/twig zendframework/zend-diactoros zendframework/zend-stratigility --dev
$ composer test
```

## Credits

* [All contributors][link-contributors]
* [Awesome PHP!](https://github.com/ziadoz/awesome-php)
* [Codeigniter](https://codeigniter.com)
* [Crux anti-framework](https://github.com/yuloh/crux)
* [Fucking Small](https://github.com/trq/fucking-small)
* [Laravel framework](https://laravel.com)
* [No Framework Tutorial](https://github.com/PatrickLouys/no-framework-tutorial)
* [PHP Design Patterns](http://designpatternsphp.readthedocs.org/en/latest)
* [Symfony framework](http://symfony.com)

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/slytherin.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/slytherin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/slytherin.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/slytherin/master.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/slytherin.svg?style=flat-square

[link-changelog]: https://github.com/rougin/slytherin/blob/master/CHANGELOG.md
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/slytherin
[link-contributors]: https://github.com/rougin/slytherin/contributors
[link-downloads]: https://packagist.org/packages/rougin/slytherin
[link-license]: https://github.com/rougin/slytherin/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/slytherin
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/slytherin/code-structure
[link-travis]: https://travis-ci.org/rougin/slytherin
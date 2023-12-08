# Slytherin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Yet Another Extensible PHP Library/Framework.

## Install

Via Composer

``` bash
$ composer require rougin/slytherin --no-dev
```

**Warning**: If you install Slytherin with dev dependencies, you will get packages that you may not use for your next web application (e.g., [Auryn](https://github.com/rdlowrey/auryn), [Diactoros](https://github.com/zendframework/zend-diactoros), etc.). These packages are required for testing only. They are not included when you run Composer with `--no-dev`.

## Usage

``` php
$components = new Rougin\Slytherin\Components;

$components
    ->setContainer(new Acme\IoC\Container)
    ->setDispatcher(new Acme\Dispatching\Dispatcher)
    ->setDebugger(new Acme\Debug\Debugger)
    ->setHttp(new Acme\Http\Request, new Acme\Http\Response);

$application = new Rougin\Slytherin\Application($components);

$application->run();
```

Regarding the example implementation above, you need to select a package of your choice and implement it with a provided interface in order for it to be integrated in Slytherin. More information about this can be found in the [Using Interfaces](https://github.com/rougin/slytherin/wiki/Using-Interfaces) section.

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

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/slytherin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/slytherin/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/slytherin.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/slytherin.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/slytherin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rougin/slytherin
[link-travis]: https://travis-ci.org/rougin/slytherin
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/slytherin/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/slytherin
[link-downloads]: https://packagist.org/packages/rougin/slytherin
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors

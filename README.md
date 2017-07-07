# Slytherin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Slytherin is a simple and extensible PHP library that tries to achieve a [SOLID](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design))-based design for creating your next web application. It uses [Composer](https://getcomposer.org) as the dependency package manager to add, update or even remove external packages.

## Install

Via Composer

``` bash
$ composer require rougin/slytherin
```

## Usage

### Using [ContainerInterface](src/Container/ContainerInterface.php)

``` php
// Define HTTP objects that is compliant to PSR-7 standards
$request = new Rougin\Slytherin\Http\ServerRequest($_SERVER);
$response = new Rougin\Slytherin\Http\Response(http_response_code());

// Create routes from Rougin\Slytherin\Routing\RouterInterface...
$router = new Rougin\Slytherin\Routing\Router;

$router->get('/', 'App\Http\Controllers\WelcomeController@index');

// ...then define it to Rougin\Slytherin\Routing\DispatcherInterface
$dispatcher = new Rougin\Slytherin\Routing\Dispatcher($router);

// Add the above objects through \Rougin\Slytherin\Container\ContainerInterface
$container = new Rougin\Slytherin\Container\Container;

$container->set('Psr\Http\Message\ServerRequestInterface', $request);
$container->set('Psr\Http\Message\ResponseInterface', $response);
$container->set('Rougin\Slytherin\Routing\DispatcherInterface', $dispatcher);

// Lastly, run the application using the definitions from the container
(new Rougin\Slytherin\Application($container))->run();
```

### Using [IntegrationInterface](src/Integration/IntegrationInterface.php)

``` php
// Specify the integrations to be included and defined
$integrations = [];

$integrations[] = 'Rougin\Slytherin\Http\HttpIntegration';
$integrations[] = 'Rougin\Slytherin\Routing\RoutingIntegration';

// Create routes from Rougin\Slytherin\Routing\RouterInterface
$router = new Rougin\Slytherin\Routing\Router;

$router->get('/', 'App\Http\Controllers\WelcomeController@index');

// Supply values to integrations through Rougin\Slytherin\Configuration
$config = (new Rougin\Slytherin\Integration\Configuration)
    ->set('app.http.server', $_SERVER)
    ->set('app.router', $router);

// Run the application using the specified integrations and configuration
(new Rougin\Slytherin\Application)->integrate($integrations, $config)->run();
```

### Run the application using PHP's built-in web server:

``` bash
$ php -S localhost:8000
```

Open your web browser and go to [http://localhost:8000](http://localhost:8000).

### Required packages

* A [PSR-7](http://www.php-fig.org/psr/psr-7) compliant HTTP package
* A [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) compliant Container package
* Any route dispatching package, must be implemented in [`DispatcherInterface`](src/Routing/DispatcherInterface.php)

Slytherin also provide implementations of the mentioned items above.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer require filp/whoops league/container nikic/fast-route phroute/phroute rdlowrey/auryn twig/twig zendframework/zend-stratigility --dev
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Inspirations

* [Awesome PHP!](https://github.com/ziadoz/awesome-php) by [Jamie York](https://github.com/ziadoz)
* [Codeigniter](https://codeigniter.com) by [EllisLab](https://ellislab.com)/[British Columbia Institute of Technology](http://www.bcit.ca)
* [Crux](https://github.com/yuloh/crux) by [Matt A](https://github.com/yuloh)
* [Fucking Small](https://github.com/trq/fucking-small) by [Tony Quilkey](https://github.com/trq)
* [Laravel](https://laravel.com) by [Taylor Otwell](https://github.com/taylorotwell)
* [No Framework Tutorial](https://github.com/PatrickLouys/no-framework-tutorial) by [Patrick Louys](https://github.com/PatrickLouys)
* [PHP Design Patterns](http://designpatternsphp.readthedocs.org/en/latest) by [Dominik Liebler](https://github.com/domnikl)
* [PHP Standard Recommendations](http://www.php-fig.org/psr) by [PHP-FIG](http://www.php-fig.org)
* [Symfony](http://symfony.com) by [SensioLabs](https://sensiolabs.com)

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

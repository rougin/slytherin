# Slytherin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Slytherin is a simple and extensible PHP micro-framework that tries to achieve a [SOLID](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design))-based design for creating your next web application. It uses [Composer](https://getcomposer.org) as the dependency package manager to add, update or even remove external packages. For more information regarding Slytherin, click the Wiki page [here](https://github.com/rougin/slytherin/wiki).

## Install

Via Composer

``` bash
$ composer require rougin/slytherin
```

## Usage

### Using ContainerInterface

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

### Using IntegrationInterface

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

### Run the application using PHP's web server:

``` bash
$ php -S localhost:8000
```

Open your web browser and go to [http://localhost:8000](http://localhost:8000).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer require filp/whoops league/container nikic/fast-route phroute/phroute rdlowrey/auryn twig/twig zendframework/zend-diactoros zendframework/zend-stratigility --dev
$ composer test
```

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

* [Rougin Royce Gutib][link-author]
* [All Contributors][link-contributors]
* [Awesome PHP!](https://github.com/ziadoz/awesome-php) by [Jamie York](https://github.com/ziadoz)
* [Codeigniter](https://codeigniter.com) by [EllisLab](https://ellislab.com)/[British Columbia Institute of Technology](http://www.bcit.ca)
* [Crux](https://github.com/yuloh/crux) by [Matt A](https://github.com/yuloh)
* [Fucking Small](https://github.com/trq/fucking-small) by [Tony Quilkey](https://github.com/trq)
* [Laravel](https://laravel.com) by [Taylor Otwell](https://github.com/taylorotwell)
* [No Framework Tutorial](https://github.com/PatrickLouys/no-framework-tutorial) by [Patrick Louys](https://github.com/PatrickLouys)
* [PHP Design Patterns](http://designpatternsphp.readthedocs.org/en/latest) by [Dominik Liebler](https://github.com/domnikl)
* [PHP Standard Recommendations](http://www.php-fig.org/psr) by [PHP-FIG](http://www.php-fig.org)
* [Symfony](http://symfony.com) by [SensioLabs](https://sensiolabs.com)

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

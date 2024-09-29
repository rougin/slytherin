# Slytherin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

Slytherin is a simple and extensible PHP micro-framework that tries to achieve a [SOLID-based design](https://en.wikipedia.org/wiki/SOLID) for creating web applications. It uses [Composer](https://getcomposer.org/) as the dependency package manager to add, update or even remove external packages.

## Background

In the current state of PHP ecosystem, the mostly used PHP frameworks like [Symfony](http://symfony.com) and [Laravel](https://laravel.com) provide a great set of tools for every PHP software engineer. While the said PHP frameworks provide a kitchen-sink solution for every need (e.g., content management system (CMS), CRUD, etc.), they are sometimes overkill, overwhelming at first, or sometimes uses a strict directory structure.

With this, Slytherin tries an alternative approach to only require the basic tools like [HTTP][link-wiki-http] and [Routing][link-wiki-routing] and let the application evolve from a simple API tool to a full-featured web application. With no defined directory structure, Slytherin can be used to mix and match any structure based on the application's requirements and to encourage the use of open-source packages in the PHP ecosystem.

## Basic Example

Below is an example code for creating a simple application using Slytherin:

``` php
// app/web/index.php

use Rougin\Slytherin\Application;

// Load the Composer autoloader ----
$root = dirname(dirname(__DIR__));

require "$root/vendor/autoload.php";
// ---------------------------------

// Create a new application instance ---
$app = new Application;
// -------------------------------------

// Create a new HTTP route ---
$app->get('/', function ()
{
    return 'Hello world!';
});
// ---------------------------

// Then run the application after ---
echo $app->run();
// ----------------------------------
```

Kindly check the [The First "Hello World"][link-wiki-example] page in the wiki for more information in the provided sample code above.

## Upgrade Guide

As Slytherin is evolving as a micro-framework, there might be some breaking changes in its internal code during development. The said changes can be found in the [Upgrade Guide][link-wiki-upgrade] page.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

To check all written test cases, kindly install the specified third-party packages first:

``` bash
$ composer request filp/whoops --dev
$ composer request league/container --dev
$ composer request nikic/fast-route --dev
$ composer request phroute/phroute --dev
$ composer request rdlowrey/auryn --dev
$ composer request twig/twig --dev
$ composer request zendframework/zend-diactoros --dev
$ composer request zendframework/zend-stratigility --dev
$ composer test
```

## Credits

Slytherin is inspired by the following packages below and their respective implementations. Their contributions improved [my understanding][link-homepage] of writing frameworks and creating application logic from scratch:

* [Awesome PHP!](https://github.com/ziadoz/awesome-php) by [Jamie York](https://github.com/ziadoz);
* [Codeigniter](https://codeigniter.com) by [EllisLab](https://ellislab.com)/[British Columbia Institute of Technology](http://www.bcit.ca);
* [Fucking Small](https://github.com/trq/fucking-small) by [Tony Quilkey](https://github.com/trq);
* [Laravel](https://laravel.com) by [Taylor Otwell](https://github.com/taylorotwell);
* [No Framework Tutorial](https://github.com/PatrickLouys/no-framework-tutorial) by [Patrick Louys](https://github.com/PatrickLouys);
* [PHP Design Patterns](http://designpatternsphp.readthedocs.org/en/latest) by [Dominik Liebler](https://github.com/domnikl);
* [PHP Standard Recommendations](http://www.php-fig.org/psr) by [PHP-FIG](http://www.php-fig.org);
* [Symfony](http://symfony.com) by [SensioLabs](https://sensiolabs.com); and
* All of the [contributors][link-contributors] in this package.

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/rougin/slytherin/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/rougin/slytherin?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/slytherin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/slytherin.svg?style=flat-square

[link-build]: https://github.com/rougin/slytherin/actions
[link-changelog]: https://github.com/rougin/slytherin/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/rougin/slytherin/contributors
[link-coverage]: https://app.codecov.io/gh/rougin/slytherin
[link-downloads]: https://packagist.org/packages/rougin/slytherin
[link-homepage]: https://roug.in
[link-license]: https://github.com/rougin/slytherin/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/slytherin
[link-wiki-example]: https://github.com/rougin/slytherin/wiki/The-First-%22Hello-World%22
[link-wiki-http]: https://github.com/rougin/slytherin/wiki/Http
[link-wiki-routing]: https://github.com/rougin/slytherin/wiki/Routing
[link-wiki-upgrade]: https://github.com/rougin/slytherin/wiki/Upgrade-Guide
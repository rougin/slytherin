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

``` php
$components = (new Rougin\Slytherin\Component\Collection)
    ->setContainer(new Acme\IoC\Container)
    ->setDispatcher(new Acme\Dispatching\Dispatcher)
    ->setDebugger(new Acme\Debug\Debugger)
    ->setHttp(new Acme\Http\Request, new Acme\Http\Response);

(new Rougin\Slytherin\Application($components))->run();
```

Regarding the example implementation above, you need to select a package of your choice and implement it with a provided interface in order for it to be integrated in Slytherin. More information about this can be found in the [Using Interfaces](https://github.com/rougin/slytherin/wiki/Using-Interfaces) section.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer require \
  filp/whoops \
  league/container \
  nikic/fast-route \
  phroute/phroute \
  rdlowrey/auryn \
  twig/twig \
  zendframework/zend-diactoros \
  zendframework/zend-stratigility \
  --dev
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## References

- [Jamie York's](https://github.com/ziadoz) [Awesome PHP!](https://github.com/ziadoz/awesome-php)
- [Dominik Liebler's](https://github.com/domnikl) [PHP Design Patterns](http://designpatternsphp.readthedocs.org/en/latest/)
- [Patrick Louys'](https://github.com/PatrickLouys/no-framework-tutorial) [No Framework Tutorial](https://github.com/PatrickLouys/no-framework-tutorial)
- [Tony R Quilkey's](https://github.com/trq) [Fucking Small Framework](https://github.com/trq/fucking-small)

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

# Changelog

All Notable changes to `Slytherin` will be documented in this file

## [Unreleased](https://github.com/rougin/slytherin/compare/v0.8.0...HEAD) - [CURRENT_DATE]

### Fixed
- Compatibility issue for `Statigility\Middleware`

### Added
- [PSR-7](http://www.php-fig.org/psr/psr-7) implementation of `Http` component
- `BaseUriGuesser` for filtering the base URI
- Unit test for `Phroute` in `Application\Application`
- Middlewares in `FastRoute\Dispatcher` and ``Phroute\Dispatcher`

### Changed
- Traits to separate classes: `ClassResolver`, `HttpModifier`, `RouteDispatcher`
- Minimum required PHP version to `v5.3.0`

## [0.8.0](https://github.com/rougin/slytherin/compare/v0.7.0...v0.8.0) - 2016-09-08

### Added
- Implementation for [Phroute](https://github.com/mrjgreen/phroute) package

### Changed
- Set globals to `Twig_Environment` when creating an instance in `Template\TwigRenderer`

### Fixed
- Using `add` in `Component\Collector` if not using `IoC\Vanilla\Container`

### Removed
- Third party packages in `require-dev`

## [0.7.0](https://github.com/rougin/slytherin/compare/v0.6.0...v0.7.0)

### Added
- HTTP method spoofing
- `Component` directory for handling components

### Changed
- Version of `filp/whoops`

### Fixed
- Returning of result when using a `Middleware` component

### Removed
- `HttpKernelInterface`

## [0.6.0](https://github.com/rougin/slytherin/compare/v0.5.0...v0.6.0)

### Added
- Parameter for adding default data and file extension in `Template\TwigRenderer`

### Changed
- File and directory structure

## [0.5.0](https://github.com/rougin/slytherin/compare/v0.4.3...v0.5.0) 2016-04-14

### Added
- `Middleware` component
- `Application::handle` and `Application::toResponse` methods
- `HttpKernelInterface` for interoperability
- `ComponentCollection` class

### Changed
- PHP version to `v5.4.0`
- Interface from `RequestInterface` to `ServerRequestInterface` in `Components`

## [0.4.3](https://github.com/rougin/slytherin/compare/v0.4.2...v0.4.3) 2016-02-19

### Added
- Setting of headers in `Response` (if any)

## [0.4.2](https://github.com/rougin/slytherin/compare/v0.4.1...v0.4.2) 2016-02-07

### Added
- [Dispatching\BaseRouter](https://github.com/rougin/slytherin/blob/v0.4.2/src/Dispatching/BaseRouter.php)

### Fixed
- Issue on parsing a route of the same URI but different HTTP method

## [0.4.1](https://github.com/rougin/slytherin/compare/v0.4.0...v0.4.1) 2016-02-01

### Added
- Implementations for packages [Auryn](https://github.com/rdlowrey/auryn), [FastRoute](https://github.com/nikic/FastRoute), [League\Container](http://container.thephpleague.com) and [Whoops](https://github.com/filp/whoops)
- Unit tests

### Changed
- Moved required packages to `require-dev` in `composer.json`

## [0.4.0](https://github.com/rougin/slytherin/compare/v0.3.0...v0.4.0) 2016-01-13

### Added
- `ComponentCollection::setContainer` for adding packages implemented on [container-interop/container-interop](https://github.com/container-interop/container-interop)'s `ContainerInterface`

### Changed
- `ErrorHandler` to `Debug`
- `ComponentCollection` to `Components`
- Renamed sample library implementations

### Removed
- `ComponentCollection::setInjector`
- `Http` directory (will now require implementations in [PSR-7](http://www.php-fig.org/psr/psr-7))

## [0.3.0](https://github.com/rougin/slytherin/compare/v0.2.1...v0.3.0) - 2015-11-02

### Added
- Interface-based library implementations

### Changed
- Code and directory structure
- Implemented SOLID-based approach

## [0.2.1](https://github.com/rougin/slytherin/compare/v0.2.0...v0.2.1) - 2015-09-30

### Added
- [`Uri`](https://github.com/rougin/slytherin/blob/v0.2.1/src/Uri.php) library in the [`View`](https://github.com/rougin/slytherin/blob/v0.2.1/src/View.php) class

### Changed
- Conformed code to PSR-2 coding standards

### Fixed
- Issues in defining and routing URLs

### Removed
- `checkDirectories` method in [`Application`](https://github.com/rougin/slytherin/blob/v0.2.1/src/Application.php) class

## [0.2.0](https://github.com/rougin/slytherin/compare/v0.1.0...v0.2.0) - 2015-07-21

### Fixed
- Installation process errors
- Non-existing namespaces

## 0.1.0 - 2015-06-17

### Added
- `Slytherin` library
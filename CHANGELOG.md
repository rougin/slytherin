# Changelog

All notable changes to `Slytherin` will be documented in this file.

## [0.9.6](https://github.com/rougin/slytherin/compare/v0.9.5...v0.9.6) - Unreleased

### Changed
- All routers is now aligned to one dispatcher only
- Add `\` when `$namespace` property is defined in `Routing\Router`

### Fixed
- Compatability of `LeagueContainer` in PHP v7.2.0

## [0.9.5](https://github.com/rougin/slytherin/compare/v0.9.4...v0.9.5) - 2017-02-23

### Changed
- Unit test cases
- `LICENSE.md`

### Fixed
- Returning templates from `Template\Renderer` with insensitive-case

### Removed
- Dependency of `Dispatcher` in `FastRouteDispatcher` and `PhrouteDispatcher`

## [0.9.4](https://github.com/rougin/slytherin/compare/v0.9.3...v0.9.4) - 2017-02-07

### Fixed
- Empty `attributes` when using `ServerRequest::getAttribute`

## [0.9.3](https://github.com/rougin/slytherin/compare/v0.9.2...v0.9.3) - 2018-02-05

### Added
- `Middleware\HandlerInterface`

### Changed
- Allowed `IntegrationInterface` instances to be added in `Application::integrate`
- Returning data in `Http\Uri::withUserInfo`
- Return headers based from `HTTP_*` values in `$_SERVER` global variable
- Rewrite logic of `Http` package
- Move `Container\Exception\NotFoundException` to `Container\NotFoundException` 
- Change `array_push` to `$array[]`
- Rewrite logic of `Template\Renderer`

### Fixed
- Running test cases without third-party packages

### Removed
- `xdebug_get_headers` and `headers_list` in `HttpIntegration`

## [0.9.2](https://github.com/rougin/slytherin/compare/v0.9.1...v0.9.2) - 2017-10-27

### Fixed
- Getting default values of a class in `Container\ReflectionContainer`
- Returning empty values in `Integration\Configuration`

## [0.9.1](https://github.com/rougin/slytherin/compare/v0.9.0...v0.9.1) - 2017-07-21

### Fixed
- Retrieving a single uploaded file in `ServerRequest::getUploadedFiles`

## [0.9.0](https://github.com/rougin/slytherin/compare/v0.8.0...v0.9.0) - 2017-07-08

**NOTE**: This release may break your application if upgrading from `v0.8.0` release.

### Fixed
- Appending of middleware response from `DispatcherInterface`'s result in `Application::run`
- `Array to string conversion` error when add callback routes with arguments
- Compatibility issue for `Statigility\Middleware`
- Getting `$request` object in container after being defined in `Application::handle`

### Added
- Implementation of [PSR-7](http://www.php-fig.org/psr/psr-7), [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md), and [PSR-15](https://github.com/php-fig/fig-standards/blob/master/proposed/http-middleware/middleware.md) standards.
    - Packages `psr/container` and `psr/http-message` are already included in this release
    - Install `http-interop/http-middleware` if you want to use middlewares in `Middleware` directory
- Middlewares in `FastRoute\Dispatcher` and `Phroute\Dispatcher`
- `Integration` for integrating third-party packages to Slytherin
- `Configuration` for ease of access in getting configurations inside integrations
- Integrations for existing directories (e.g `Http\HttpIntegration`, `Debug\ErrorHandlerIntegration`)
- `Routing\Router::prefix` for adding prefix in succeeding route endpoints
- `Routing\Router::restful` for adding RESTful routes based on one base route
- `Routing\DispatcherInterface::router` for setting up routers manually
- `Application::container` for getting the static instance of [PSR-11](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md) container
- `Container\ReflectionContainer` for using PHP's [Reflection API](http://php.net/manual/en/book.reflection.php) for solving class dependencies
- Resolving of type hinted parameters in functions or class methods
- `Application\CallbackHandler` and `Application\FinalCallback` for building a callback for the application

### Changed
- Minimum required PHP version to `v5.3.0`
- `Dispatching` directory to `Routing`
- `IoC` directory to `Container`
- `Debug\Debugger` to `Debug\ErrorHandler`
- `Middleware\MiddlewareInterface` to `Middleware\DispatcherInterface`
- Emit headers on `Application::run` only
- Allow anonymous functions and objects in adding middleware from a specified route in `Middleware\Stratigility`

### Deprecated
- `Application\Application` class
- `Component` directory
- `Debug\Debugger` class
- `Debugger` related classes
- `Dispatching` directory
- `ErrorHandler` directory
- `IoC` directory
- `Middleware\MiddlewareInterface` interface
- `Middleware\Stratigility\Middleware` class
- `Template\Twig\Renderer` class
- `Vanilla` related classes

### Removed
- HTTP method spoofing (apply it on a [middleware](https://github.com/rougin/weasley/blob/master/src/Http/Middleware/FormMethodSpoofing.php) instead)
- Traits (in order to achieve PHP `v5.3.0` as the minimum required version)
- `getEnvironment` and `setEnvironment` in `Debug\ErrorHandlerInterface`

## [0.8.0](https://github.com/rougin/slytherin/compare/v0.7.0...v0.8.0) - 2016-09-08

### Added
- Implementation for [Phroute](https://github.com/mrjgreen/phroute) package

### Changed
- Set globals to `Twig_Environment` when creating an instance in `Template\TwigRenderer`

### Fixed
- Using `add` in `Component\Collector` if not using `IoC\Vanilla\Container`

### Removed
- Third party packages in `require-dev`

## [0.7.0](https://github.com/rougin/slytherin/compare/v0.6.0...v0.7.0) - 2016-07-17

### Added
- HTTP method spoofing
- `Component` directory for handling components

### Changed
- Version of `filp/whoops`

### Fixed
- Returning of result when using a `Middleware` component

### Removed
- `HttpKernelInterface`

## [0.6.0](https://github.com/rougin/slytherin/compare/v0.5.0...v0.6.0) - 2016-05-24

### Added
- Parameter for adding default data and file extension in `Template\TwigRenderer`
- `Vanilla` directories (e.g `Dispatching\Vanilla`, `IoC\Vanilla`)

### Changed
- File and directory structure

## [0.5.0](https://github.com/rougin/slytherin/compare/v0.4.3...v0.5.0) - 2016-04-14

### Added
- `Middleware` component
- `Application::handle` and `Application::toResponse` methods
- `HttpKernelInterface` for interoperability
- `ComponentCollection` class

### Changed
- PHP version to `v5.4.0`
- Interface from `RequestInterface` to `ServerRequestInterface` in `Components`

## [0.4.3](https://github.com/rougin/slytherin/compare/v0.4.2...v0.4.3) - 2016-02-19

### Added
- Setting of headers in `Response` (if any)

## [0.4.2](https://github.com/rougin/slytherin/compare/v0.4.1...v0.4.2) - 2016-02-07

### Added
- [Dispatching\BaseRouter](https://github.com/rougin/slytherin/blob/v0.4.2/src/Dispatching/BaseRouter.php)

### Fixed
- Issue on parsing a route of the same URI but different HTTP method

## [0.4.1](https://github.com/rougin/slytherin/compare/v0.4.0...v0.4.1) - 2016-02-01

### Added
- Implementations for packages [Auryn](https://github.com/rdlowrey/auryn), [FastRoute](https://github.com/nikic/FastRoute), [League\Container](http://container.thephpleague.com) and [Whoops](https://github.com/filp/whoops)
- Unit tests

### Changed
- Moved required packages to `require-dev` in `composer.json`

## [0.4.0](https://github.com/rougin/slytherin/compare/v0.3.0...v0.4.0) - 2016-01-13

**NOTE**: This release will break your application if upgrading from `v0.3.0` release.

### Added
- `ComponentCollection::setContainer` for adding packages implemented on `Interop\Container\ContainerInterface`

### Fixed
- Getting value of an argument from a callback route

### Changed
- `ErrorHandler` to `Debug`
- `ComponentCollection` to `Components`
- Renamed sample package implementations

### Removed
- `ComponentCollection::setInjector` (use `ComponentCollection::setContainer` instead)
- `Http` directory (will now require implementations in [PSR-7](http://www.php-fig.org/psr/psr-7))
- `DependencyInjectorInterface` (will now require implementations in `Interop\Container\ContainerInterface`)
- `Http\ResponseInterface` dependency in `Dispatching\Dispatcher`
- Dependency of [nikic/fast-route](https://github.com/nikic/FastRoute) in `Dispatching` (use `Dispatching\FastRoute` instead)

## [0.3.0](https://github.com/rougin/slytherin/compare/v0.2.1...v0.3.0) - 2015-11-02

**NOTE**: This release will break your application if upgrading from `v0.2.0` release.

### Added
- Interface-based package implementations

### Changed
- Code and directory structure to a library type
- Implemented [SOLID](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design))-based approach

### Removed
- Almost everything, this release will be no longer an application skeleton

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
- Installation process errors (needs an existing `composer.lock` to trigger the installation)
- Non-existing namespaces

## 0.1.0 - 2015-06-17

### Added
- `Slytherin` framework
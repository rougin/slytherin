# Changelog

All Notable changes to `Slytherin` will be documented in this file

## [0.4.0](https://github.com/rougin/slytherin/compare/v0.3.0...v0.4.0)

### Added
- `ComponentCollection::setContainer` for adding libraries that is implemented in [container-interop/container-interop](https://github.com/container-interop/container-interop)'s `ContainerInterface`

### Changed
- Renamed sample library implementations

### Removed
- `Http` directory (Will now adopt [PSR-7](http://www.php-fig.org/psr/psr-7/) implementations)
- `ComponentCollection::setInjector`

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
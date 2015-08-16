# Slytherin

[![Latest Stable Version](https://poser.pugx.org/rougin/slytherin/v/stable)](https://packagist.org/packages/rougin/slytherin) [![Total Downloads](https://poser.pugx.org/rougin/slytherin/downloads)](https://packagist.org/packages/rougin/slytherin) [![Latest Unstable Version](https://poser.pugx.org/rougin/slytherin/v/unstable)](https://packagist.org/packages/rougin/slytherin) [![License](https://poser.pugx.org/rougin/slytherin/license)](https://packagist.org/packages/rougin/slytherin) [![endorse](https://api.coderwall.com/rougin/endorsecount.png)](https://coderwall.com/rougin)

Heavily inspired by this awesome [tutorial](https://github.com/PatrickLouys/no-framework-tutorial), Slytherin is a simple and extensible PHP library that tries to achieve a SOLID-based design for creating small and large applications. Unlike the other frameworks that provides big libraries that you don't need in developing your web applications, this library utilizes [Composer](https://getcomposer.org) as the dependency package manager to add, update or even remove external libraries with ease.

# Installation

1. This library requires [Composer](https://getcomposer.org) and [Git](http://git-scm.com) in order to get it work. Download and install the latest version of it first. The instructions for that can be found [here](http://git-scm.com/downloads) and [here](https://getcomposer.org/download/). If you have already installed Composer and Git on your system, then you can skip this step.

2. Add the ```Slytherin``` package in your ```require``` list in ```composer.json```:

    ```json
    {
      "require": {
        "rougin/slytherin": "dev-master"
      },
      "autoload": {
        "psr-4": {
          "App\\": "src/"
        }
      },
      "scripts": {
        "post-install-cmd": [
          "Rougin\\Slytherin\\Installer::deploy"
        ]
      }
    }
    ```

    Then run ```$ composer install```

3. Aaaand you're done! Try to experiment this library with other libraries that currently exists on [Packagist](https://packagist.org/) (or even here at [awesome-php](https://github.com/ziadoz/awesome-php)) and create an awesome and cool PHP project! You can also share your set of libraries in the [Wiki section](https://github.com/rougin/slytherin/wiki)! :smile:

Found a bug? Want to contribute? Feel free to open an issue or create a pull request. :+1:
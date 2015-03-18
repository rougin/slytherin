[![endorse](https://api.coderwall.com/rougin/endorsecount.png)](https://coderwall.com/rougin)

# Slytherin

Slytherin is a simple and extensible PHP library that follows an MVC software architectural pattern for creating small projects. Unlike the other frameworks that provides big libraries that you don't need in developing your web applications, this library utilizes [Composer](https://getcomposer.org) as the dependency package manager to add, update or even remove external libraries with ease.

# Installation Instructions

1. This library requires [Composer](https://getcomposer.org) and [Git](http://git-scm.com) in order to get it work. Download and install the latest version of it first. The instructions for that can be found [here](http://git-scm.com/downloads) and [here](https://getcomposer.org/download/). If you have already installed Composer and Git on your system, then you can skip this step.

2. Add the ```Slytherin``` package in your ```require``` list in ```composer.json```:

	```json
	{
		"require": {
			"rougin/slytherin": "dev-master"
		},
		"autoload": {
			"psr-4": {
				"Controllers\\": "app/controllers",
				"Libraries\\": "app/libraries",
				"Models\\": "app/models"
			}
		}
	}
	```

	Then run ```$ composer install```

3. After the installation, run this command from the [PHP CLI](http://php.net/manual/en/features.commandline.php):

	**For Unix and Mac**

	```$ php vendor/bin/slytherize```
	
	**For Windows or if there are no symbolic links found at ```vendor/bin``` directory**

	```$ php vendor/rougin/slytherin/bin/slytherize```

4. Aaaand you're done! Try to experiment this library with other libraries that currently exists on [Packagist](https://packagist.org/) (or even here at [awesome-php](https://github.com/ziadoz/awesome-php)) and create an awesome and cool PHP project! You can also share your set of libraries in the Wiki section :smile:

Found a bug? Want to contribute? Feel free to open an issue or create a pull request. :+1:
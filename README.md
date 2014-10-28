<a href="https://coderwall.com/rougin"><img alt="Endorse rougin on Coderwall" src="https://api.coderwall.com/rougin/endorsecount.png" /></a>

Slytherin
=========

Slytherin is a simple and extensible PHP library that follows an MVC software architectural pattern for creating small projects. Unlike the other frameworks that provides big libraries that you don't need in developing your web applications, this library utilizes [Composer](https://getcomposer.org) to add, update or even remove external libraries with ease.

Installation Instructions
============
1. This script requires [Composer](https://getcomposer.org) and [Git](http://git-scm.com) in order to get it work. Download and install the latest version of it first. The instructions for that can be found [here](http://git-scm.com/downloads) and [here](https://getcomposer.org/download/). If you have already installed Composer and Git on your system, then you can skip this step.
2. Add the Slytherin package in your require-list in ```composer.json``` and then run ```composer install```:

  ```
  {
      "require": {
          "rougin/slytherin": "dev-master"
      }
  }
  ```
  
3. After the installation, run this command from the PHP CLI:

  For Unix and Mac:

	```php vendor/bin/accio```
	
  For Windows or if there are no symbolic links found at ```vendor/bin``` directory:

  ```php vendor/rougin/slytherin/bin/accio```

4. Aaaand you're done! Try to experiment this library with other libraries that currently exists on [Packagist](https://packagist.org/) (or in [GitHub](https://github.com/search?utf8=%E2%9C%93&q=php+library)!) and create an awesome and cool PHP project! :smile:

License
=======

The MIT License (MIT)

Copyright (c) 2014

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
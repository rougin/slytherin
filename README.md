[![endorse](https://api.coderwall.com/rougin/endorsecount.png)](https://coderwall.com/rougin)

# Slytherin

Slytherin is a simple and extensible PHP library that follows an MVC software architectural pattern for creating small projects. Unlike the other frameworks that provides big libraries that you don't need in developing your web applications, this library utilizes [Composer](https://getcomposer.org) as the dependency package manager to add, update or even remove external libraries with ease.

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

# Usage

Let's use a table named ```account``` and it contains 3 columns:

* ```id```       integer
* ```name```     string
* ```username``` string

**app/controllers/Accounts.php**

```php
namespace Controllers;

use Slytherin\Controller;
use Slytherin\View;
use Models\Account;

class Accounts extends Controller {

	public function index()
	{
		$account = new Account();
		$data['accounts'] = $account->getAll();

		return View::render('accounts/index', $data);
	}
	
}
```

**app/config/database.php**

```php
/**
 * Database Configuration
 * 
 * This is where you will setup your database credentials that
 * will be used by Slytherin\Model class. Take note that it will
 * use the PDO driver that is already included in PHP.
 */

// Include your database credentials below

return array(
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => '',
	'driver'   => 'mysql'
);
```

**app/models/Account.php**

```php
namespace Models;

use Slytherin\Model;

class Account extends Model {

	public static function getAll()
	{
		$accounts = array();

		$statement = $this->databaseHandle->prepare('SELECT * FROM account');
		$statement->execute();
		$statement->setFetchMode(\PDO::FETCH_OBJ);

		while ($row = $statement->fetch()) {
			$accounts[] = $row;
		}

		return $accounts;
	}

}
```

```$this->databaseHandle``` is an instance of a ```PDO``` object. For more information about ```PDO```, you can view it [here](http://code.tutsplus.com/tutorials/why-you-should-be-using-phps-pdo-for-database-access--net-12059).

**app/views/accounts/index.php**

```php
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Username</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($accounts as $account): ?>
			<tr>
				<td><?php echo $account->name; ?></td>
				<td><?php echo $account->username; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
```

## Creating a Library

**app/libraries/SampleLibrary.php**

```php
namespace Libraries

class SampleLibrary {
	
	public static function sayHello($name) {
		return 'Hello ' . $name . '!';
	}

}

```

To call the library, just use the alias ```Libraries\SampleLibrary```:

```php
namespace Controllers;

use Slytherin\Controller;
use Slytherin\View;
use Libraries\SampleLibrary;

/**
 * Welcome Controller Class
 */

class Welcome extends Controller {

	public function index()
	{
		$name = 'Slytherin';

		return SampleLibrary::sayHello($name);
	}

}
```

Found a bug? Want to contribute? Feel free to open an issue or create a pull request. :+1:
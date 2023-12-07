# Slytherin

[![Latest Stable Version](https://poser.pugx.org/rougin/slytherin/v/stable)](https://packagist.org/packages/rougin/slytherin) [![Total Downloads](https://poser.pugx.org/rougin/slytherin/downloads)](https://packagist.org/packages/rougin/slytherin) [![Latest Unstable Version](https://poser.pugx.org/rougin/slytherin/v/unstable)](https://packagist.org/packages/rougin/slytherin) [![License](https://poser.pugx.org/rougin/slytherin/license)](https://packagist.org/packages/rougin/slytherin) [![endorse](https://api.coderwall.com/rougin/endorsecount.png)](https://coderwall.com/rougin)

Slytherin is a simple and extensible PHP library that follows an MVC software architectural pattern for creating small projects. Unlike the other frameworks that provides big libraries that you don't need in developing your web applications, this library utilizes [Composer](https://getcomposer.org) as the dependency package manager to add, update or even remove external libraries with ease.

# Installation

1. This library requires [Composer](https://getcomposer.org) and [Git](http://git-scm.com) in order to get it work. Download and install the latest version of it first. The instructions for that can be found [here](http://git-scm.com/downloads) and [here](https://getcomposer.org/download/). If you have already installed Composer and Git on your system, then you can skip this step.

2. Add the ```Slytherin``` package in your ```require``` list in ```composer.json```:

    ```json
    {
      "require": {
        "rougin/slytherin": "*"
      },
      "autoload": {
        "psr-4": {
          "Controllers\\": "app/controllers",
          "Libraries\\": "app/libraries",
          "Models\\": "app/models"
        }
      },
      "scripts": {
        "post-update-cmd": [
          "Rougin\\Slytherin\\Installer::deploy"
        ]
      }
    }
    ```

    Then run ```$ composer install```

3. Aaaand you're done! Try to experiment this library with other libraries that currently exists on [Packagist](https://packagist.org/) (or even here at [awesome-php](https://github.com/ziadoz/awesome-php)) and create an awesome and cool PHP project! You can also share your set of libraries in the [Wiki section](https://github.com/rougin/slytherin/wiki)! :smile:

# Usage

Create a table named `users` with the following fields:

* `id` as `integer`
* `email` as `string`
* `name` as `string`
* `created_at` as `datetime`
* `updated_at` as `datetime`

``` sql
DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO users (email, NAME, created_at) VALUES
('rougingutib@gmail.com', 'Rougin Gutib', CURRENT_TIMESTAMP),
('me@slytherin.dev', 'Slytherin', CURRENT_TIMESTAMP),
('test@gmail.com', 'Test', CURRENT_TIMESTAMP),
('me@roug.in', 'Royce Gutib', CURRENT_TIMESTAMP);
```

After creating the database table and populating the data, create the following code for `CRUD`:

``` php
// app/controllers/Users.php

namespace Controllers;

use Models\User;
use Rougin\Slytherin\Controller;
use Rougin\Slytherin\View;

class Users extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = new User;
    }

    public function index()
    {
        $data = array();

        $page = $_GET['page'] ?? 1;

        $limit = $_GET['limit'] ?? 10;

        $items = $this->user->get($page, $limit);

        $data['items'] = $items;

        return View::render('users/index', $data);
    }
}
```

```php
// app/config/databases.php

/**
 * Database Configuration
 * 
 * This is where you will setup your database credentials that
 * will be used by Rougin\Slytherin\Model class. Take note that it will
 * use the PDO driver that is already included in PHP.
 */

// Include your database credentials below

return array(
    'default' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => '',
        'driver'   => 'mysql'
    )
);
```

``` php
// app/models/User.php

namespace Models;

use Rougin\Slytherin\Model;

class User extends Model
{
    public function get($page = 1, $limit = 10)
    {
        $pdo = $this->databaseHandle;

        $query = 'SELECT * FROM users';

        $offset = ($page - 1) * $limit;

        $query .= " LIMIT $limit OFFSET $offset";

        $st = $pdo->prepare($query);

        $st->execute();

        return $st->fetchAll(\PDO::FETCH_ASSOC);
    }
}
```

```$this->databaseHandle``` is an instance of a ```PDO``` object. For more information about ```PDO```, you can view it [here](http://code.tutsplus.com/tutorials/why-you-should-be-using-phps-pdo-for-database-access--net-12059).

You can also specify the database connection to be used in a model. For example, if I want to load my ```Account``` model with another database connection rather than the ```default``` connection, I'll just add a new element to the ```databases.php```:

**app/config/databases.php**

```php
// app/config/databases.php

// ...

return array(
    'default' => /** ... */,
    'my_another_connection' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => '',
        'driver'   => 'mysql'
    )
);
```

Next, add the said connection in the ```Account``` model as a parameter:

```php
// app/controllers/Accounts.php

$account = new Account('my_another_connection');
$data['accounts'] = $account->getAll();
```

```php
// app/views/accounts/index.php

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

After creating the required `CRUD` code, add its required route on `app/config/routes.php`:

``` php
// ...

$route->get('/users', 'Controllers\\Users:index');

/**
 * Default controller
 */

// ...
```

### Creating a Library

**app/libraries/SampleLibrary.php**

```php
namespace Libraries;

/**
 * Sample Library
 * 
 * @category Libraries
 */
class SampleLibrary {
    
    public static function sayHello($name) {
        return 'Hello ' . $name . '!';
    }

}
```

To call the library, just use the alias ```Libraries\SampleLibrary```:

```php
namespace Controllers;

use Rougin\Slytherin\Controller;
use Rougin\Slytherin\View;
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

# Libraries used

Slytherin utilizes third-party libraries that can be found on [Github](https://github.com/ziadoz/awesome-php) to prevent us from [reinventing the wheel](http://en.wikipedia.org/wiki/Reinventing_the_wheel). We encourage you to use the other libraries freely available on Internet to be able to learn new technologies and to save your time in developing your precious web application!

Here are the list of libraries that we currently used:

* [Config](https://github.com/hassankhan/config) - a file configuration loader that supports PHP, INI, XML, JSON, and YML files
* [Pux](https://github.com/c9s/Pux) - a high performance PHP router
* [Twig](http://twig.sensiolabs.org/) - A comprehensive templating language
* [Whoops!](https://github.com/filp/whoops) - PHP Error for cool kids

We want to make a documentation about those libraries in here, but it's like *reinventing the wheel* right? :smile: So if you want to learn more about those libraries, just go to their respective ```README.md``` or their Wiki pages!

Found a bug? Want to contribute? Feel free to open an issue or create a pull request. :+1:
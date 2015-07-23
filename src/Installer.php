<?php namespace Rougin\Slytherin;

use Composer\Installer\PackageEvent;
use Composer\Script\Event;

/**
 * Installer Class
 *
 * @package Slytherin
 */
class Installer 
{
	/**
	 * Run the post installation process
	 * 
	 * @return integer
	 */
	public static function deploy(Event $event = null)
	{
		/**
		 * Creates a series of directories
		 */

		$directories = array(
			'app/',
			'app/config/',
			'app/controllers/',
			'app/libraries/',
			'app/models/',
			'app/views/'
		);

		foreach ($directories as $directory) {
			if ( ! file_exists($directory)) {
				mkdir($directory);
			}

			if ( ! file_exists($directory . 'index.html')) {
				$errorMessage = 'There is something wrong to the page you requested!';
				$file = fopen($directory . 'index.html', 'wb');

				file_put_contents($directory . 'index.html', $errorMessage);
				fclose($file);
			}
		}

		/**
		 * Create the "Welcome" controller and "view" files
		 * and also a user-friendly error page
		 */

		if ( ! file_exists('app/controllers/Welcome.php')) {
			$welcome = file_get_contents(__DIR__ . '/Templates/Welcome.txt');

			$file = fopen('app/controllers/Welcome.php', 'wb');
			file_put_contents('app/controllers/Welcome.php', $welcome);
			fclose($file);
		}

		if ( ! file_exists('app/views/welcome/')) {
			mkdir('app/views/welcome/');

			$welcomeIndex = 'Hello world!';

			$file = fopen('app/views/welcome/index.php', 'wb');
			file_put_contents('app/views/welcome/index.php', $welcomeIndex);
			fclose($file);
		}

		if ( ! file_exists('app/views/404.php')) {
			$errorMessage = 'There is something wrong to the page you requested!';

			$file = fopen('app/views/404.php', 'wb');
			file_put_contents('app/views/404.php', $errorMessage);
			fclose($file);
		}

		/**
		 * Creates a .htacess for clean urls
		 */

		$htaccess = file_get_contents(__DIR__ . '/Templates/Htaccess.txt');

		$file = fopen('.htaccess', 'wb');
		chmod('.htaccess', 0777);
		file_put_contents('.htaccess', $htaccess);
		fclose($file);

		/**
		 * Creates an index.php as the centralized entry point for handling requests
		 */

		if ( ! file_exists('index.php')) {
			$index = file_get_contents(__DIR__ . '/Templates/Index.txt');

			$file = fopen('index.php', 'wb');
			chmod('index.php', 0777);
			file_put_contents('index.php', $index);
			fclose($file);
		}

		/**
		 * Create a databases.php for storing database credentials
		 */

		if ( ! file_exists('app/config/databases.php')) {
			$database = file_get_contents(__DIR__ . '/Templates/Databases.txt');

			$file = fopen('app/config/databases.php', 'wb');
			file_put_contents('app/config/databases.php', $database);
			fclose($file);
		}

		/**
		 * Create a routes.php for handling the user specified routes
		 */

		if ( ! file_exists('app/config/routes.php')) {
			$routes = file_get_contents(__DIR__ . '/Templates/Routes.txt');

			$file = fopen('app/config/routes.php', 'wb');
			file_put_contents('app/config/routes.php', $routes);
			fclose($file);
		}
	}
}
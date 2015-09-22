<?php

namespace Rougin\Slytherin\Installation;

use Composer\Script\Event;

/**
 * Installer Class
 *
 * Creates directories and files for Slytherin
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Installer
{
	/**
	 * Run the post installation process
	 * 
	 * @param  Event|null $event
	 * @return void
	 */
	public static function deploy(Event $event = null)
	{
		/**
		 * Creates a series of directories
		 */
		
		$directories = [
			'app/',
			'app/resources/',
			'app/resources/views/',
			'public/',
			'src/',
			'src/Components/',
			'src/Controllers/',
			'src/Models/'
		];

		$installationDirectory = __DIR__ . '/Installation';

		foreach ($directories as $directory) {
			if ( ! file_exists($directory)) {
				mkdir($directory);
			}
		}

		/**
		 * Creates a .htacess for clean urls
		 */

		$htaccess = file_get_contents($installationDirectory . '/Htaccess.txt');

		$file = fopen('public/.htaccess', 'wb');
		chmod('public/.htaccess', 0777);
		file_put_contents('public/.htaccess', $htaccess);
		fclose($file);

		/**
		 * Creates an index.php as the centralized entry point for handling requests
		 */

		if ( ! file_exists('public/index.php')) {
			$index = file_get_contents($installationDirectory . '/Index.txt');

			$file = fopen('public/index.php', 'wb');
			file_put_contents('public/index.php', $index);
			fclose($file);
		}

		/**
		 * Create a components.php for handling the user specified components
		 */

		if ( ! file_exists('app/components.php')) {
			$components = file_get_contents($installationDirectory . '/Components.txt');

			$file = fopen('app/components.php', 'wb');
			file_put_contents('app/components.php', $components);
			fclose($file);
		}

		/**
		 * Create a database.php for storing database credentials
		 */

		if ( ! file_exists('app/database.php')) {
			$database = file_get_contents($installationDirectory . '/Databases.txt');

			$file = fopen('app/database.php', 'wb');
			file_put_contents('app/database.php', $database);
			fclose($file);
		}

		/**
		 * Create a routes.php for handling the user specified routes
		 */

		if ( ! file_exists('app/routes.php')) {
			$routes = file_get_contents($installationDirectory . '/Routes.txt');

			$file = fopen('app/routes.php', 'wb');
			file_put_contents('app/routes.php', $routes);
			fclose($file);
		}

		/**
		 * Create a Bootstrap.php for bootstrapping the application
		 */

		if ( ! file_exists('src/Bootstrap.php')) {
			$routes = file_get_contents($installationDirectory . '/Bootstrap.txt');

			$file = fopen('src/Bootstrap.php', 'wb');
			file_put_contents('src/Bootstrap.php', $routes);
			fclose($file);
		}
	}
}

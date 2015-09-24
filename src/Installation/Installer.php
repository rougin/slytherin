<?php

namespace Rougin\Slytherin\Installation;

/**
 * Installer Class
 *
 * Creates sample directories and files for Slytherin
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Installer
{
	/**
	 * Run the post installation process
	 * 
	 * @return void
	 */
	public static function deploy()
	{
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

		$files = [
			'app/components.php' => 'Components.txt',
			'app/databases.php' => 'Databases.txt',
			'app/routes.php' => 'Routes.txt',
			'public/.htaccess' => 'Htaccess.txt',
			'public/index.php' => 'Index.txt',
			'src/Bootstrap.php' => 'Bootstrap.txt'
		];

		$installationPath = __DIR__ . '/Installation';

		foreach ($directories as $directory) {
			if ( ! file_exists($directory)) {
				mkdir($directory);
			}
		}

		foreach ($files as $file => $template) {
			File::create($file, $installationPath . '/' . $template);
		}
	}
}

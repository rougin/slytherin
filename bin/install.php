<?php

/**
 * Creates a series of directories
 */

if ( ! file_exists('app/')) {
	mkdir('app/');

	if ( ! file_exists('app/controllers/')) {
		mkdir('app/controllers');
	}
	
	if ( ! file_exists('app/models/')) {
		mkdir('app/models');
	}

	if ( ! file_exists('app/views/')) {
		mkdir('app/views');
	}
}

/**
 * Creates a .htacess for clean urls
 */

$htaccess =
'RewriteEngine on
Options +FollowSymLinks
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule .* index.php/$0 [PT,L]';

$file = fopen('.htaccess', 'wb');
chmod('.htaccess', 0777);
file_put_contents('.htaccess', $htaccess);
fclose($file);

/**
 * Creates an index.php as the centralized entry point for handling requests
 */

$index =
'<?php

include \'vendor/autoload.php\';

/**
 * Load the router
 */

$router = new Slytherin\Router();

/**
 * Initialize and Run Slytherin
 */

$app = new Slytherin\Application($router);
$app->run();';

$file = fopen('.index', 'wb');
chmod('.index', 0777);
file_put_contents('.index', $index);
fclose($file);
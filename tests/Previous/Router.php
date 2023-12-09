<?php

use Rougin\Slytherin\Dispatching\Router;

$name = 'Rougin\Slytherin\Previous\Routes';

$router = new Router;

$router->addRoute('GET', '/', [ "$name\Hello", 'index' ]);

$router->addRoute('GET', '/hi/:name', [ "$name\Hello", 'hi' ]);

// Add the middlewares to a specified route ---------------------------
$items = array('Rougin\Slytherin\Previous\Handlers\Hello');

$router->addRoute('GET', '/hello', function () { return ''; }, $items);
// --------------------------------------------------------------------

return $router;

<?php

/** @deprecated since ~0.9, use "Routing\Router" instead of "Dispatching\Router". */

use Rougin\Slytherin\Dispatching\Router;

$name = 'Rougin\Slytherin\Previous\Routes';

$router = new Router;

$router->addRoute('GET', '/hi/:name', array("$name\Hello", 'hi'));

$router->addRoute('GET', '/', array("$name\Hello", 'index'));

// Add the middlewares to a specified route -------
$item = 'Rougin\Slytherin\Previous\Handlers\Hello';

$items = array($item);

$fn = function ()
{
    return '';
};

$router->addRoute('GET', '/hello', $fn, $items);
// ------------------------------------------------

return $router;

<?php

namespace Rougin\Slytherin;

$middlewares = array();

$middlewares['0.3.0'] = 'Interop\Http\Middleware\ServerMiddlewareInterface';

$middlewares['0.4.1'] = 'Interop\Http\ServerMiddleware\MiddlewareInterface';

$middlewares['0.5.0'] = 'Interop\Http\Server\MiddlewareInterface';

$middlewares['1.0.0'] = 'Psr\Http\Server\MiddlewareInterface';

$middleware = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

$defined = interface_exists((string) $middleware);

foreach ((array) array_keys($middlewares) as $version)
{
    $interface = $middlewares[$version];

    $exists = interface_exists($interface);

    if ($defined === false && $exists)
    {
        class_alias($interface, $middleware);
    }
}

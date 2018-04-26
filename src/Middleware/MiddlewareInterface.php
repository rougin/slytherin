<?php

namespace Rougin\Slytherin;

// @codeCoverageIgnoreStart
$middlewares = array();

$middlewares['0.3.0'] = 'Interop\Http\Middleware\ServerMiddlewareInterface';
$middlewares['0.4.1'] = 'Interop\Http\ServerMiddleware\MiddlewareInterface';
$middlewares['0.5.0'] = 'Interop\Http\Server\MiddlewareInterface';
$middlewares['1.0.0'] = 'Psr\Http\Server\MiddlewareInterface';

foreach ((array) array_keys($middlewares) as $version) {
    $exists = interface_exists($current = $middlewares[$version]);

    $middleware = 'Rougin\Slytherin\Middleware\MiddlewareInterface';

    $defined = interface_exists((string) $middleware);

    ! $defined && $exists && class_alias($current, $middleware);
}
// @codeCoverageIgnoreEnd

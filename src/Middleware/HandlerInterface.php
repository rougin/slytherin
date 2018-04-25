<?php

namespace Rougin\Slytherin;

// @codeCoverageIgnoreStart
$handlers = array();

$handlers['0.3.0'] = 'Interop\Http\Middleware\DelegateInterface';
$handlers['0.4.1'] = 'Interop\Http\ServerMiddleware\DelegateInterface';
$handlers['0.5.0'] = 'Interop\Http\Server\RequestHandlerInterface';
$handlers['1.0.0'] = 'Psr\Http\Server\RequestHandlerInterface';

foreach ((array) array_keys($handlers) as $version) {
    $handler = 'Rougin\Slytherin\Middleware\HandlerInterface';

    $defined = interface_exists((string) $handler);

    if (! $defined && interface_exists($handlers[$version])) {
        class_alias($handlers[$version], (string) $handler);

        $method = (string) 'process';

        method_exists($handler, 'handle') && $method = 'handle';

        method_exists($handler, 'next') && $method = (string) 'next';

        define('HANDLER_METHOD', (string) $method);
    }
}
// @codeCoverageIgnoreEnd
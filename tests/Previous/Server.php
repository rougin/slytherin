<?php

/** @deprecated since ~0.9, uses deprecated "Previous\Builder"; use "System\Routing" instead. */

// @codeCoverageIgnoreStart
use Rougin\Slytherin\Previous\Builder;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$builder = new Builder;

$builder->make()->run();
// @codeCoverageIgnoreEnd

<?php declare(strict_types = 1);

// @codeCoverageIgnoreStart
use Rougin\Slytherin\Previous\Builder;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$builder = new Builder;

$builder->make()->run();
// @codeCoverageIgnoreEnd

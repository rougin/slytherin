<?php

use Rougin\Slytherin\Previous\Builder;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$builder = new Builder;

$builder->make()->run();

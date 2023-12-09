<?php

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Component\Collector;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$items = array();

$items[] = 'Rougin\Slytherin\Fixture\Components\DebuggerComponent';
$items[] = 'Rougin\Slytherin\Fixture\Components\DispatcherComponent';
$items[] = 'Rougin\Slytherin\Fixture\Components\HttpComponent';

$components = Collector::get($items);

$app = new Application($components);

$app->run();

<?php

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Component\Collector;
use Rougin\Slytherin\Container\Container;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$items = array();

$items[] = 'Rougin\Slytherin\Fixture\Components\CollectionComponent';
$items[] = 'Rougin\Slytherin\Fixture\Components\DebuggerComponent';
$items[] = 'Rougin\Slytherin\Fixture\Components\DispatcherComponent';
$items[] = 'Rougin\Slytherin\Fixture\Components\HttpComponent';
$items[] = 'Rougin\Slytherin\Fixture\Components\SingleComponent';

$container = new Container;

$components = Collector::get($container, $items, $globals);

$app = new Application($components);

$app->run();

<?php

// @codeCoverageIgnoreStart
use Rougin\Slytherin\Sample\Builder;
use Rougin\Slytherin\Sample\Handlers\Cors;
use Rougin\Slytherin\Sample\Packages\SamplePackage;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$builder = new Builder;

$builder->setCookies($_COOKIE);
$builder->setFiles($_FILES);
$builder->setQuery($_GET);
$builder->setParsed($_POST);
$builder->setServer($_SERVER);

$builder->addPackage(new SamplePackage);
$builder->addHandler(new Cors);

$builder->make()->run();
// @codeCoverageIgnoreEnd
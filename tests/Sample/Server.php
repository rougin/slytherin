<?php

// @codeCoverageIgnoreStart
use Rougin\Slytherin\Sample\Builder;
use Rougin\Slytherin\Sample\Handlers\Cors;
use Rougin\Slytherin\Sample\Packages\SamplePackage;

$root = dirname(dirname(__DIR__));

require $root . '/vendor/autoload.php';

$builder = new Builder;

/** @var array<string, string> */
$cookies = $_COOKIE;
$builder->setCookies($cookies);

/** @var array<string, array<string, mixed[]>> */
$files = $_FILES;
$builder->setFiles($files);

/** @var array<string, string> */
$get = $_GET;
$builder->setQuery($get);

/** @var array<string, string> */
$post = $_POST;
$builder->setParsed($post);

/** @var array<string, string> */
$server = $_SERVER;
$builder->setServer($server);

$builder->addPackage(new SamplePackage);
$builder->addHandler(new Cors);

$builder->make()->run();
// @codeCoverageIgnoreEnd

<?php

/**
 * This file is only to be used for "PHPstan".
 * For its autoloading, kindly see "Interop".
 */

// @codeCoverageIgnoreStart
$container = 'Rougin\Slytherin\Container';

$number = '\V2';

$orig = $container . $number . '\Container';
class_alias($orig, $container . '\PsrContainer');

$orig = $container . $number . '\ReflectionContainer';
class_alias($orig, $container . '\PsrReflectionContainer');

$orig = $container . $number . '\AurynContainer';
class_alias($orig, $container . '\PsrAurynContainer');

$orig = $container . $number . '\LeagueContainer';
class_alias($orig, $container . '\PsrLeagueContainer');
// @codeCoverageIgnoreEnd

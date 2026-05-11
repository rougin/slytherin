<?php

/**
 * This file is only to be used for "PHPstan".
 * For its autoloading, kindly see "Interop".
 */

// @codeCoverageIgnoreStart
use Rougin\Slytherin\Container\Interop;

$base = 'Rougin\Slytherin\Container';

$number = Interop::isVersion2() ? '\V2' : '\V1';

$orig = $base . $number . '\ReflectionContainer';
class_alias($orig, $base . '\PsrReflectionContainer');

$orig = $base . $number . '\Container';
class_alias($orig, $base . '\PsrContainer');

if (class_exists('League\Container\Container'))
{
    $orig = $base . $number . '\LeagueContainer';
    class_alias($orig, $base . '\PsrLeagueContainer');
}

if (class_exists('Auryn\Injector'))
{
    $orig = $base . $number . '\AurynContainer';
    class_alias($orig, $base . '\PsrAurynContainer');
}
// @codeCoverageIgnoreEnd

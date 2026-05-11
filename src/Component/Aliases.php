<?php

/**
 * This file is only to be used for "PHPstan".
 * For its autoloading, kindly see "Interop".
 */

// @codeCoverageIgnoreStart
use Rougin\Slytherin\Component\Interop;

$base = 'Rougin\Slytherin\Component';

$number = Interop::isVersion2() ? '\V2' : '\V1';

$orig = $base . $number . '\Collection';

class_alias($orig, $base . '\PsrCollection');
// @codeCoverageIgnoreEnd

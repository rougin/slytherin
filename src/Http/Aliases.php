<?php

/**
 * This file is only to be used for "PHPstan".
 * For its autoloading, kindly see "Interop".
 */

// @codeCoverageIgnoreStart
use Rougin\Slytherin\Http\Interop;

$http = 'Rougin\Slytherin\Http';

$number = Interop::isVersion2() ? '\V2' : '\V1';

$orig = $http . $number . '\Message';
class_alias($orig, $http . '\PsrMessage');

$orig = $http . $number . '\Request';
class_alias($orig, $http . '\PsrRequest');

$orig = $http . $number . '\Response';
class_alias($orig, $http . '\PsrResponse');

$orig = $http . $number . '\ServerRequest';
class_alias($orig, $http . '\PsrServerRequest');

$orig = $http . $number . '\Stream';
class_alias($orig, $http . '\PsrStream');

$orig = $http . $number . '\UploadedFile';
class_alias($orig, $http . '\PsrUploadedFile');

$orig = $http . $number . '\Uri';
class_alias($orig, $http . '\PsrUri');
// @codeCoverageIgnoreEnd

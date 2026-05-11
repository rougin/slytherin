<?php

/**
 * This file is only to be used for "PHPstan".
 * For its autoloading, kindly see "Interop".
 */

// @codeCoverageIgnoreStart
$class = 'Psr\Http\Message\MessageInterface';

$method = 'getProtocolVersion';

$class = new ReflectionMethod($class, $method);

$isV2 = method_exists($class, 'hasReturnType')
    && $class->hasReturnType();

$http = 'Rougin\Slytherin\Http';

$number = $isV2 ? '\V2' : '\V1';

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

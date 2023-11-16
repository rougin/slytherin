<?php

// Add backward compatability for the said class ------------------------------------------------
if (! class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase'))
{
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}
// ----------------------------------------------------------------------------------------------

require __DIR__ . '/../vendor/autoload.php';
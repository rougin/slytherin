<?php

namespace Rougin\Slytherin\Sample\Depots;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class SestDepot
{
    protected $test;

    public function __construct(TestDepot $test)
    {
        $this->test = $test;
    }

    public function text($data)
    {
        return $this->test->text($data);
    }
}
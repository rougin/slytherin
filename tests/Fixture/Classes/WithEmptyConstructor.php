<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * With Empty Constructor
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WithEmptyConstructor
{
    public function __construct()
    {
    }

    /**
     * Returns a string 'Hello'.
     *
     * @return string
     */
    public function index()
    {
        return 'Hello';
    }
}

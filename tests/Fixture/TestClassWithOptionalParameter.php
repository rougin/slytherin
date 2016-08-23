<?php

namespace Rougin\Slytherin\Test\Fixture;

/**
 * Test Class With Optional Parameter
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestClassWithOptionalParameter
{
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name = 'Rougin')
    {
        $this->name = $name;
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

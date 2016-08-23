<?php

namespace Rougin\Slytherin\Test\Fixture;

/**
 * Test Class With Interface
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestClassWithInterface implements TestInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @param string $name
     */
    public function __construct($name = 'Rougin')
    {
        $this->name = $name;
    }
}

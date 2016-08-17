<?php

namespace Rougin\Slytherin\Test\Fixture;

/**
 * Test Class With Parameter
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestClassWithParameter
{
    /**
     * @var \Rougin\Slytherin\Test\Fixture\TestClass
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Test\Fixture\TestClass $class
     */
    public function __construct(TestClass $class)
    {
        $this->class = $class;
    }
}

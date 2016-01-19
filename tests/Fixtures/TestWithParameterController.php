<?php

namespace Rougin\Slytherin\Test\Fixtures;

/**
 * Test With Parameter Controller
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestWithParameterController
{
    /**
     * @var \Rougin\Slytherin\Test\Fixtures\TestClass
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Test\Fixtures\TestClass $class
     */
    public function __construct(TestClass $class)
    {
        $this->class = $class;
    }
}

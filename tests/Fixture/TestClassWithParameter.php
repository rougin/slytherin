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
     * @var \Rougin\Slytherin\Test\Fixture\TestAnotherClass
     */
    protected $another;

    /**
     * @var \Rougin\Slytherin\Test\Fixture\TestClass
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Test\Fixture\TestClass $class
     * @param \Rougin\Slytherin\Test\Fixture\TestAnotherClass $another
     */
    public function __construct(TestClass $class, TestAnotherClass $another)
    {
        $this->class = $class;
        $this->another = $another;
    }

    public function index()
    {
        return $this->class->index();
    }
}

<?php

namespace Rougin\Slytherin\Test\Fixture;

/**
 * Test Class With Interface Parameter
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TestClassWithInterfaceParameter
{
    /**
     * @var \Rougin\Slytherin\Test\Fixture\TestInterface
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Test\Fixture\TestInterface $class
     */
    public function __construct(TestInterface $class)
    {
        $this->class = $class;
    }
}

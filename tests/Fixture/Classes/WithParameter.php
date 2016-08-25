<?php

namespace Rougin\Slytherin\Test\Fixture\Classes;

/**
 * With Parameter
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WithParameter
{
    /**
     * @var \Rougin\Slytherin\Test\Fixture\Classes\AnotherClass
     */
    protected $another;

    /**
     * @var \Rougin\Slytherin\Test\Fixture\Classes\NewClass
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Test\Fixture\Classes\NewClass     $class
     * @param \Rougin\Slytherin\Test\Fixture\Classes\AnotherClass $another
     */
    public function __construct(NewClass $class, AnotherClass $another)
    {
        $this->class   = $class;
        $this->another = $another;
    }

    /**
     * Returns with a string of "Hello".
     *
     * @return string
     */
    public function index()
    {
        return $this->class->index();
    }
}

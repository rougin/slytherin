<?php

namespace Rougin\Slytherin\Test\Fixture\Classes;

/**
 * With Interface Parameter
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WithInterfaceParameter
{
    /**
     * @var \Rougin\Slytherin\Test\Fixture\Classes\NewInterface
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Test\Fixture\Classes\NewInterface $class
     */
    public function __construct(NewInterface $class)
    {
        $this->class = $class;
    }
}

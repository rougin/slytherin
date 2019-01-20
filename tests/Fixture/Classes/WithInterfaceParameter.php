<?php

namespace Rougin\Slytherin\Fixture\Classes;

/**
 * With Interface Parameter
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class WithInterfaceParameter
{
    /**
     * @var \Rougin\Slytherin\Fixture\Classes\NewInterface
     */
    protected $class;

    /**
     * @param \Rougin\Slytherin\Fixture\Classes\NewInterface $class
     */
    public function __construct(NewInterface $class)
    {
        $this->class = $class;
    }
}

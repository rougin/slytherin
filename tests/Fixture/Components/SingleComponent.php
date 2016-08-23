<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Rougin\Slytherin\Test\Fixture\TestClass;
use Rougin\Slytherin\Component\AbstractComponent;

/**
 * Single Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class SingleComponent extends AbstractComponent
{
    /**
     * Name of the class to be added in the container.
     *
     * @var string
     */
    protected $className = 'Rougin\Slytherin\Test\Fixture\TestClass';

    /**
     * Returns an instance from the named class.
     *
     * @return mixed
     */
    public function get()
    {
        return new TestClass;
    }
}

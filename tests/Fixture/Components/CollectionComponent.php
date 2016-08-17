<?php

namespace Rougin\Slytherin\Test\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;

use Rougin\Slytherin\Test\Fixture\TestClass;
use Rougin\Slytherin\Test\Fixture\TestClassWithEmptyConstructor;

/**
 * Collection Component
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class CollectionComponent extends AbstractComponent
{
    /**
     * Returns an instance from the named class.
     * 
     * @return mixed
     */
    public function get()
    {
        return [ new TestClass, new TestClassWithEmptyConstructor ];
    }
}

<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;

use Rougin\Slytherin\Fixture\TestClass;
use Rougin\Slytherin\Fixture\TestClassWithEmptyConstructor;

/**
 * Collection Component
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class CollectionComponent extends AbstractComponent
{
    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        return array(new TestClass, new TestClassWithEmptyConstructor);
    }
}

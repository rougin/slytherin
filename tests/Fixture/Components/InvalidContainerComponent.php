<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class InvalidContainerComponent extends ContainerComponent
{
    /**
     * @return mixed
     */
    public function get()
    {
        return new NewClass;
    }
}

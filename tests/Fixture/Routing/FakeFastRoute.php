<?php

namespace Rougin\Slytherin\Fixture\Routing;

use Rougin\Slytherin\Fixture\Classes\NewClass;

/**
 * @codeCoverageIgnore
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FakeFastRoute
{
    /**
     * @param string $method
     * @param string $uri
     *
     * @return array<integer, mixed>
     */
    public function dispatch($method, $uri)
    {
        return array(1, new NewClass, array());
    }
}

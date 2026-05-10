<?php

namespace Rougin\Slytherin\Middleware\Stratigility;

use Rougin\Slytherin\Middleware\MiddlewareTestCases;
use Zend\Stratigility\MiddlewarePipe;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class MiddlewareTest extends MiddlewareTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfStratigilityExists();

        $stack = array();

        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\FirstMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\SecondMiddleware';
        $stack[] = 'Rougin\Slytherin\Fixture\Middlewares\LastMiddleware';

        $pipeline = new MiddlewarePipe;

        $this->self = new Middleware($pipeline, $stack);
    }
}

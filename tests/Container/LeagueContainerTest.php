<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class LeagueContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\LeagueContainer
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_not_found_exception_thrown()
    {
        $expect = 'Psr\Container\NotFoundExceptionInterface';

        $this->doSetExpectedException($expect);

        $this->self->get('Rougin\Slytherin\HelloWorld');
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->self->set($class, new $class);

        $this->assertTrue($this->self->has($class));
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_retrieved()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Register the class as a shared instance --
        $this->self->set($expect, new $expect, true);
        // ------------------------------------------

        // Verify the object is of the expected class ---
        $actual = $this->self->get($expect);

        $this->assertInstanceOf($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfLeagueExists();

        $this->self = new LeagueContainer;
    }
}

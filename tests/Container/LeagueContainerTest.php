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
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_not_found_exception_thrown()
    {
        $expect = 'Psr\Container\NotFoundExceptionInterface';

        $this->doSetExpectedException($expect);

        // Attempt to get a non-existent class ---
        $class = 'Rougin\Slytherin\HelloWorld';

        $this->container->get($class);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Set a class instance in the container ---
        $this->container->set($class, new $class);
        // -----------------------------------------

        // Verify the instance is available -------------
        $this->assertTrue($this->container->has($class));
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_retrieved()
    {
        $expect = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Register the class as a shared instance -------
        $this->container->set($expect, new $expect, true);
        // -----------------------------------------------

        // Verify the object is of the expected class ---
        $actual = $this->container->get($expect);

        $this->assertInstanceOf($expect, $actual);
        // ----------------------------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfLeagueExists();

        $this->container = new LeagueContainer;
    }
}

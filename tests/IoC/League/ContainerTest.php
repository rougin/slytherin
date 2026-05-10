<?php

namespace Rougin\Slytherin\IoC\League;

use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\IoC\League\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $class = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

    /**
     * @var \Rougin\Slytherin\Fixture\Classes\WithParameter
     */
    protected $instance;

    /**
     * @return void
     */
    public function test_passed_if_class_added()
    {
        // Add a class instance ---
        $this->container->add($this->class, $this->instance);
        // ------------------------

        // Verify the class exists ---
        $this->assertTrue($this->container->has($this->class));
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_class_exists()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        // Add a class without an instance ---
        $this->container->add($class);
        // ----------------------------------

        // Verify the class exists ---
        $this->assertTrue($this->container->has($class));
        // --------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_retrieved()
    {
        // Set a class instance ----------
        $this->container->set($this->class, $this->instance);
        // -------------------------------

        // Verify the retrieved instance matches ---
        $expect = $this->instance;

        $actual = $this->container->get($this->class);

        $this->assertEquals($expect, $actual);
        // -------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_set()
    {
        // Set a class instance directly ---
        $this->container->set($this->class, $this->instance);
        // ----------------------------------

        // Verify the class exists ---
        $this->assertTrue($this->container->has($this->class));
        // --------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfLeagueExists();

        $this->container = new Container;

        $this->instance = new WithParameter(new NewClass, new AnotherClass);
    }
}

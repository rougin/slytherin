<?php

namespace Rougin\Slytherin\IoC\Auryn;

use Auryn\Injector;
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
     * @var \Rougin\Slytherin\IoC\Auryn\Container
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
    public function test_failed_if_class_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\Exception\NotFoundException';

        $this->doSetExpectedException($expect);

        // Attempt to get a non-existent class ---
        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentClass');
        // ----------------------------------------
    }

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
        // Add a class instance --------
        $this->container->add($this->class, $this->instance);
        // -----------------------------

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
    public function test_passed_if_multi_parameter_added()
    {
        // Add a class with multiple parameter hints ---
        $this->container->add($this->class, array(':class' => new NewClass));
        // -----------------------------------------------

        // Verify the class exists ---
        $this->assertTrue($this->container->has($this->class));
        // --------------------------
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        $this->checkIfAurynExists();
        // @codeCoverageIgnoreEnd

        $this->container = new Container(new Injector);

        $this->instance = new WithParameter(new NewClass, new AnotherClass);
    }
}

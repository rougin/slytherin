<?php

namespace Rougin\Slytherin\IoC\Vanilla;

use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithInterface;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * @deprecated since ~0.9, use "Container\ContainerTest" instead.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Fixture\Classes\WithParameter
     */
    protected $class;

    /**
     * @var string
     */
    protected $name = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

    /**
     * @var \Rougin\Slytherin\IoC\Vanilla\Container
     */
    protected $self;

    /**
     * @return void
     */
    public function test_failed_if_class_does_not_exist()
    {
        $expect = 'Rougin\Slytherin\Container\Exception\NotFoundException';

        $this->doExpectException($expect);

        $this->self->get('Rougin\Slytherin\Hello');
    }

    /**
     * @return void
     */
    public function test_failed_if_container_exception_raised()
    {
        $expect = 'Rougin\Slytherin\Container\Exception\ContainerException';

        $this->doExpectException($expect);

        // Set a non-class value and retrieve it ---
        $this->self->set('Foo', array());

        $this->self->get('Foo');
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_failed_if_reflection_container_error()
    {
        $self = new ReflectionContainer($this->self);

        $expect = 'Rougin\Slytherin\Container\Exception\NotFoundException';

        // Attempt to get an unknown class via reflection ---
        $this->doExpectException($expect);

        $self->get('Test');
        // --------------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_alias_set()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithInterface';

        $interface = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        // Register a class and create an alias ---
        $this->self->add($class, new $class);

        $this->self->alias($interface, $class);
        // ----------------------------------------

        // Verify the alias resolves correctly ---
        $actual = $this->self->has($interface);

        $this->assertTrue($actual);
        // ---------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_class_added()
    {
        $this->self->add($this->name, $this->class);

        $this->assertTrue($this->self->has($this->name));
    }

    /**
     * @return void
     */
    public function test_passed_if_class_exists()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->self->add($class, new $class);

        $this->assertTrue($this->self->has($class));
    }

    /**
     * @return void
     */
    public function test_passed_if_empty_constructor_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithEmptyConstructor';

        $this->self->add($class, new $class);

        $this->assertTrue($this->self->has($class));
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_found()
    {
        // Add a class with parameters -------------
        $this->self->add($this->name, $this->class);
        // -----------------------------------------

        // Verify the retrieved instance matches ---
        $expect = $this->class;

        $actual = $this->self->get($this->name);

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_interface_resolved()
    {
        $withParam = 'Rougin\Slytherin\Fixture\Classes\WithInterfaceParameter';

        $interface = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        // Resolve an interface and use it as a dependency ---
        $this->self->add($interface, new WithInterface);

        $class = $this->self->get($interface);

        $this->self->add($withParam, $class);
        // ---------------------------------------------------

        // Verify the dependent class was resolved -----
        $this->assertTrue($this->self->has($withParam));
        // ---------------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_optional_parameter_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter';

        $this->self->add($class, new $class);

        $this->assertTrue($this->self->has($class));
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_added()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->self->add($class, new $class);

        $this->assertTrue($this->self->has($class));
    }

    /**
     * @return void
     */
    public function test_passed_if_simple_class_resolved()
    {
        $this->self->add($this->name, $this->class);

        $this->assertTrue($this->self->has($this->name));
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->self = new Container;

        $class = new WithParameter(new NewClass, new AnotherClass);

        $this->class = $class;
    }
}

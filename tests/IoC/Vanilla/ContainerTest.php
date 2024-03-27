<?php

namespace Rougin\Slytherin\IoC\Vanilla;

use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithInterface;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\IoC\Vanilla\Container
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
    protected function doSetUp()
    {
        $this->container = new Container;

        $this->instance = new WithParameter(new NewClass, new AnotherClass);
    }

    /**
     * @return void
     */
    public function test_adding_a_simple_class()
    {
        $this->container->add($this->class, $this->instance);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * @return void
     */
    public function test_adding_a_class_without_a_parameter()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * @return void
     */
    public function test_adding_a_class_with_an_optional_parameter()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * @return void
     */
    public function test_adding_a_class_with_a_parameter()
    {
        $this->container->add($this->class, $this->instance);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * @return void
     */
    public function test_setting_alias_to_a_class()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithInterface';

        $interface = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        $this->container->add($class, new $class);

        $this->container->alias($interface, $class);

        $this->assertTrue($this->container->has($interface));
    }

    /**
     * @return void
     */
    public function test_getting_a_simple_class()
    {
        $this->container->add($this->class, $this->instance);

        $expected = $this->instance;

        $actual = $this->container->get($this->class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_checking_a_class_with_no_constructor()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithEmptyConstructor';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * @return void
     */
    public function test_getting_class_that_doesnt_exists()
    {
        $this->setExpectedException('Rougin\Slytherin\Container\Exception\NotFoundException');

        // NOTE: Remove ReflectionContainer as the default $extra in Container in v1.0.0.
        // $this->container->get($this->class);

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentClass');
    }

    /**
     * @return void
     */
    public function test_getting_class_with_an_error()
    {
        $this->setExpectedException('Rougin\Slytherin\Container\Exception\ContainerException');

        $this->container->set('Foo', array());

        $this->container->get('Foo');
    }

    /**
     * @return void
     */
    public function test_getting_class_as_an_interface()
    {
        $withParam = 'Rougin\Slytherin\Fixture\Classes\WithInterfaceParameter';

        $interface = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        $this->container->add($interface, new WithInterface);

        $this->container->add($withParam, $this->container->get($interface));

        $this->assertTrue($this->container->has($withParam));
    }

    /**
     * @return void
     */
    public function test_checking_class_exists()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * @return void
     */
    public function test_reflection_container_with_an_error()
    {
        $container = new ReflectionContainer($this->container);

        $this->setExpectedException('Rougin\Slytherin\Container\Exception\NotFoundException');

        $container->get('Test');
    }
}

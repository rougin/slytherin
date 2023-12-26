<?php

namespace Rougin\Slytherin\IoC\Vanilla;

use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithInterface;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * Container Test
 * NOTE: To be removed in v1.0.0.
 *
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
     * Sets up the container.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;

        $this->instance = new WithParameter(new NewClass, new AnotherClass);
    }

    /**
     * Tests if the added instance exists.
     *
     * @return void
     */
    public function testAddMethod()
    {
        $this->container->add($this->class, $this->instance);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * Tests add() method without a parameter.
     *
     * @return void
     */
    public function testAddMethodWithNoParameter()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests add() method without a parameter.
     *
     * @return void
     */
    public function testAddMethodWithOptionalParameter()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithOptionalParameter';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests add() method with a concrete as a parameter.
     *
     * @return void
     */
    public function testAddMethodWithConcreteParameter()
    {
        $this->container->add($this->class, $this->instance);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * Tests alias() method.
     *
     * @return void
     */
    public function testAliasMethod()
    {
        $class     = 'Rougin\Slytherin\Fixture\Classes\WithInterface';
        $interface = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        $this->container->add($class, new $class)->alias($interface, $class);

        $this->assertTrue($this->container->has($interface));
    }

    /**
     * Tests if the specified instance can be returned.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $this->container->add($this->class, $this->instance);

        $this->assertEquals($this->instance, $this->container->get($this->class));
    }

    /**
     * Tests get() method with an empty constructor.
     *
     * @return void
     */
    public function testGetMethodWithEmptyConstructor()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithEmptyConstructor';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests get() method with NotFoundException.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->setExpectedException('Rougin\Slytherin\Container\Exception\NotFoundException');

        // NOTE: Remove ReflectionContainer as the default $extra in Container in v1.0.0.
        // $this->container->get($this->class);

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentClass');
    }

    /**
     * Tests get() method with ContainerException.
     *
     * @return void
     */
    public function testGetMethodWithContainerException()
    {
        $this->setExpectedException('Rougin\Slytherin\Container\Exception\ContainerException');

        $this->container->set('Foo', array());

        $this->container->get('Foo');
    }

    /**
     * Tests get() method with an interface.
     *
     * @return void
     */
    public function testGetMethodWithInterface()
    {
        $class              = 'Rougin\Slytherin\Fixture\Classes\WithInterface';
        $classWithParameter = 'Rougin\Slytherin\Fixture\Classes\WithInterfaceParameter';
        $interface          = 'Rougin\Slytherin\Fixture\Classes\NewInterface';

        $this->container->add($interface, new WithInterface);
        $this->container->add($classWithParameter, $this->container->get($interface));

        $this->assertTrue($this->container->has($classWithParameter));
    }

    /**
     * Tests if the added instance exists.
     *
     * @return void
     */
    public function testHasMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests ReflectionContainer::get() method with NotFoundException.
     *
     * @return void
     */
    public function testReflectionContainerGetMethodWithNotFoundException()
    {
        $container = new \Rougin\Slytherin\Container\ReflectionContainer($this->container);

        $this->setExpectedException('Rougin\Slytherin\Container\Exception\NotFoundException');

        $container->get('Test');
    }
}

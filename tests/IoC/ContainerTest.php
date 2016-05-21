<?php

namespace Rougin\Slytherin\Test\IoC;

use Rougin\Slytherin\IoC\Container;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\TestClass;
use Rougin\Slytherin\Test\Fixture\TestClassWithInterface;
use Rougin\Slytherin\Test\Fixture\TestClassWithParameter;

/**
 * Container Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\IoC\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $class = 'Rougin\Slytherin\Test\Fixture\TestClassWithParameter';

    /**
     * Sets up the container.
     *
     * @return void
     */
    public function setUp()
    {
        $this->container = new Container;
    }

    /**
     * Tests if the added instance exists.
     * 
     * @return void
     */
    public function testAddMethod()
    {
        $this->container->add($this->class);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * Tests add() method without a parameter.
     * 
     * @return void
     */
    public function testAddMethodWithNoParameter()
    {
        $class = 'Rougin\Slytherin\Test\Fixture\TestClass';

        $this->container->add($class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests add() method without a parameter.
     * 
     * @return void
     */
    public function testAddMethodWithOptionalParameter()
    {
        $class = 'Rougin\Slytherin\Test\Fixture\TestClassWithOptionalParameter';

        $this->container->add($class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests add() method with a concrete as a parameter.
     * 
     * @return void
     */
    public function testAddMethodWithConcreteParameter()
    {
        $this->container->add(
            $this->class,
            new TestClassWithParameter(new TestClass)
        );

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * Tests alias() method.
     * 
     * @return void
     */
    public function testAliasMethod()
    {
        $class = 'Rougin\Slytherin\Test\Fixture\TestClassWithInterface';
        $interface = 'Rougin\Slytherin\Test\Fixture\TestInterface';

        $this->container
            ->add($class)
            ->alias($interface, $class);

        $this->assertTrue($this->container->has($interface));
    }

    /**
     * Tests if the specified instance can be returned.
     * 
     * @return void
     */
    public function testGetMethod()
    {
        $this->container->add($this->class);

        $this->assertEquals(
            new TestClassWithParameter(new TestClass),
            $this->container->get($this->class)
        );
    }

    /**
     * Tests get() method with an empty constructor.
     * 
     * @return void
     */
    public function testGetMethodWithEmptyConstructor()
    {
        $class = 'Rougin\Slytherin\Test\Fixture\TestClassWithEmptyConstructor';

        $this->container->add($class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests get() method with an error.
     * 
     * @return void
     */
    public function testGetMethodWithError()
    {
        $this->setExpectedException(
            'Rougin\Slytherin\IoC\Vanilla\Exception\NotFoundException'
        );

        $this->container->get($this->class);
    }

    /**
     * Tests get() method with an interface.
     * 
     * @return void
     */
    public function testGetMethodWithInterface()
    {
        $interface = 'Rougin\Slytherin\Test\Fixture\TestInterface';
        $class = 'Rougin\Slytherin\Test\Fixture\TestClassWithInterface';
        $classWithParameter = 'Rougin\Slytherin\Test\Fixture\TestClassWithInterfaceParameter';

        $this->container
            ->add($interface, new TestClassWithInterface)
            ->add($classWithParameter);

        $this->assertTrue($this->container->has($classWithParameter));
    }

    /**
     * Tests if the added instance exists.
     * 
     * @return void
     */
    public function testHasMethod()
    {
        $class = 'Rougin\Slytherin\Test\Fixture\TestClass';

        $this->container->add($class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests if the container is implemented in ContainerInterface.
     * 
     * @return void
     */
    public function testContainerInterface()
    {
        $interface = 'Rougin\Slytherin\IoC\ContainerInterface';

        $this->assertInstanceOf($interface, $this->container);
    }
}

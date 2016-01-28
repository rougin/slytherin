<?php

namespace Rougin\Slytherin\Test\IoC;

use Rougin\Slytherin\IoC\AurynContainer;

use Auryn\Injector;
use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\TestClass;
use Rougin\Slytherin\Test\Fixture\TestClassWithParameter;

/**
 * Auryn Container Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends PHPUnit_Framework_TestCase
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
        $this->container = new AurynContainer;
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
     * Tests add() method with parameters.
     * 
     * @return void
     */
    public function testAddMethodWithParameters()
    {
        $this->container->add(
            $this->class,
            [ ':class' => new TestClass ]
        );

        $this->assertTrue($this->container->has($this->class));
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
     * Tests get() method with an error.
     * 
     * @return void
     */
    public function testGetMethodWithError()
    {
        $this->setExpectedException(
            'Rougin\Slytherin\IoC\Exception\NotFoundException'
        );

        $this->container->get($this->class);
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

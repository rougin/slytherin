<?php

namespace Rougin\Slytherin\Test\IoC\Auryn;

use Auryn\Injector;
use Rougin\Slytherin\IoC\AurynContainer;
use Rougin\Slytherin\Test\Fixture\TestClass;
use Rougin\Slytherin\Test\Fixture\TestAnotherClass;
use Rougin\Slytherin\Test\Fixture\TestClassWithParameter;

use PHPUnit_Framework_TestCase;

/**
 * Auryn Container Test
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
        if ( ! class_exists('Auryn\Injector')) {
            $this->markTestSkipped('Auryn is not installed.');
        }

        if ( ! interface_exists('Interop\Container\ContainerInterface')) {
            $this->markTestSkipped('Container Interop is not installed.');
        }

        $this->container = new AurynContainer(new Injector);
    }

    /**
     * Tests if the added instance exists.
     * 
     * @return void
     */
    public function testAddMethod()
    {
        $this->container->add($this->class, new TestClassWithParameter(new TestClass, new TestAnotherClass));

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
            new TestClassWithParameter(new TestClass, new TestAnotherClass)
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
            new TestClassWithParameter(new TestClass, new TestAnotherClass),
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
            'Rougin\Slytherin\IoC\Vanilla\Exception\NotFoundException'
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
}

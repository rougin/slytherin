<?php

namespace Rougin\Slytherin\IoC\Auryn;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\WithParameter;

/**
 * Auryn Container Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\IoC\ContainerInterface
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
    public function setUp()
    {
        if (! class_exists('Auryn\Injector')) {
            $this->markTestSkipped('Auryn is not installed.');
        }

        if (! interface_exists('Psr\Container\ContainerInterface')) {
            $this->markTestSkipped('Container Interop is not installed.');
        }

        $this->container = new \Rougin\Slytherin\IoC\Auryn\Container(new \Auryn\Injector);
        $this->instance  = new WithParameter(new NewClass, new AnotherClass);
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
     * Tests add() method with parameters.
     *
     * @return void
     */
    public function testAddMethodWithParameters()
    {
        $this->container->add($this->class, array(':class' => new NewClass));

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

        $this->assertEquals($this->instance, $this->container->get($this->class));
    }

    /**
     * Tests get() method with an error.
     *
     * @return void
     */
    public function testGetMethodWithError()
    {
        $this->setExpectedException('Rougin\Slytherin\Container\Exception\NotFoundException');

        $this->container->get($this->class);
    }

    /**
     * Tests if the added instance exists.
     *
     * @return void
     */
    public function testHasMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->add($class);

        $this->assertTrue($this->container->has($class));
    }
}

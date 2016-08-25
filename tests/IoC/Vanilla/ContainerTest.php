<?php

namespace Rougin\Slytherin\Test\IoC\Vanilla;

use Rougin\Slytherin\IoC\Container;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\Classes\NewClass;
use Rougin\Slytherin\Test\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Test\Fixture\Classes\WithInterface;
use Rougin\Slytherin\Test\Fixture\Classes\WithParameter;

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
    protected $class = 'Rougin\Slytherin\Test\Fixture\Classes\WithParameter';

    /**
     * @var \Rougin\Slytherin\Test\Fixture\Classes\WithParameter
     */
    protected $instance;

    /**
     * Sets up the container.
     *
     * @return void
     */
    public function setUp()
    {
        if (! interface_exists('Interop\Container\ContainerInterface')) {
            $this->markTestSkipped('Container Interop is not installed.');
        }

        $this->container = new Container;
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
     * Tests add() method without a parameter.
     *
     * @return void
     */
    public function testAddMethodWithNoParameter()
    {
        $class = 'Rougin\Slytherin\Test\Fixture\Classes\NewClass';

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
        $class = 'Rougin\Slytherin\Test\Fixture\Classes\WithOptionalParameter';

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
        $class     = 'Rougin\Slytherin\Test\Fixture\Classes\WithInterface';
        $interface = 'Rougin\Slytherin\Test\Fixture\Classes\NewInterface';

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
        $class = 'Rougin\Slytherin\Test\Fixture\Classes\WithEmptyConstructor';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }

    /**
     * Tests get() method with an error.
     *
     * @return void
     */
    public function testGetMethodWithError()
    {
        $this->setExpectedException('Rougin\Slytherin\IoC\Vanilla\Exception\NotFoundException');

        $this->container->get($this->class);
    }

    /**
     * Tests get() method with an interface.
     *
     * @return void
     */
    public function testGetMethodWithInterface()
    {
        $class              = 'Rougin\Slytherin\Test\Fixture\Classes\WithInterface';
        $classWithParameter = 'Rougin\Slytherin\Test\Fixture\Classes\WithInterfaceParameter';
        $interface          = 'Rougin\Slytherin\Test\Fixture\Classes\NewInterface';

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
        $class = 'Rougin\Slytherin\Test\Fixture\Classes\NewClass';

        $this->container->add($class, new $class);

        $this->assertTrue($this->container->has($class));
    }
}

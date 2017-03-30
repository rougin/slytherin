<?php

namespace Rougin\Slytherin\IoC\Vanilla;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\WithInterface;
use Rougin\Slytherin\Fixture\Classes\WithParameter;

/**
 * Container Test
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
        if (! interface_exists('Psr\Container\ContainerInterface')) {
            $this->markTestSkipped('Container Interop is not installed.');
        }

        $this->container = new \Rougin\Slytherin\IoC\Vanilla\Container;
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
}

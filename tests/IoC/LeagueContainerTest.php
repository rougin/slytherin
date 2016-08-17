<?php

namespace Rougin\Slytherin\Test\IoC;

use Rougin\Slytherin\IoC\LeagueContainer;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\TestClass;
use Rougin\Slytherin\Test\Fixture\TestClassWithParameter;

/**
 * League Container Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class LeagueContainerTest extends PHPUnit_Framework_TestCase
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
        $this->container = new LeagueContainer;
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
     * Tests if the specified instance can be returned.
     * 
     * @return void
     */
    public function testGetMethod()
    {
        $testClass = 'Rougin\Slytherin\Test\Fixture\TestClass';

        $this->container->add($testClass);

        $this->container->add($this->class)
            ->withArgument($testClass);

        $this->assertEquals(
            new TestClassWithParameter(new TestClass),
            $this->container->get($this->class)
        );
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

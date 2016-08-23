<?php

namespace Rougin\Slytherin\Test\IoC\League;

use Rougin\Slytherin\IoC\LeagueContainer;

use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixture\TestClass;
use Rougin\Slytherin\Test\Fixture\TestAnotherClass;
use Rougin\Slytherin\Test\Fixture\TestClassWithParameter;

/**
 * League Container Test
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
        if (! class_exists('League\Container\Container')) {
            $this->markTestSkipped('League Container is not installed.');
        }

        $this->container = new LeagueContainer;
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
     * Tests if the specified instance can be returned.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $this->container->add($this->class)
            ->withArgument(new TestClass)
            ->withArgument(new TestAnotherClass);

        $this->assertEquals(
            new TestClassWithParameter(new TestClass, new TestAnotherClass),
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
}

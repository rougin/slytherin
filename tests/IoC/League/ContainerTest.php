<?php

namespace Rougin\Slytherin\IoC\League;

use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\WithParameter;

/**
 * League Container Test
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
        if (! class_exists('League\Container\Container')) {
            $this->markTestSkipped('League Container is not installed.');
        }

        $this->container = new \Rougin\Slytherin\IoC\League\Container;
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
     * Tests if the specified instance can be returned.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $this->container->add($this->class)
            ->withArgument(new NewClass)
            ->withArgument(new AnotherClass);

        $this->assertEquals($this->instance, $this->container->get($this->class));
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

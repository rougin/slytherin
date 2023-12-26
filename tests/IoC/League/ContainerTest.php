<?php

namespace Rougin\Slytherin\IoC\League;

use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * League Container Test
 * NOTE: To be removed in v1.0.0.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\IoC\League\Container
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
        // @codeCoverageIgnoreStart
        if (! class_exists('League\Container\Container'))
        {
            $this->markTestSkipped('League Container is not installed.');
        }
        // @codeCoverageIgnoreEnd

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
     * Tests if the defined instance exists.
     *
     * @return void
     */
    public function testSetMethod()
    {
        $this->container->set($this->class, $this->instance);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * Tests if the specified instance can be returned.
     *
     * @return void
     */
    public function testGetMethod()
    {
        // Should only use methods found in ContainerInterface ---
        // $this->container->add($this->class)
        //     ->withArgument(new NewClass)
        //     ->withArgument(new AnotherClass);
        // -------------------------------------------------------

        $this->container->set($this->class, $this->instance);

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

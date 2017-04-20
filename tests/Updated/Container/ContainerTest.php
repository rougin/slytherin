<?php

namespace Rougin\Slytherin\Updated\Container;

/**
 * Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * Sets up the container.
     *
     * @return void
     */
    public function setUp()
    {
        $this->container = new \Rougin\Slytherin\Container\Container;
    }

    /**
     * Tests ContainerInterface::alias.
     *
     * @return void
     */
    public function testAliasMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($class, new $class);

        $this->container->alias('test', $class);

        $this->assertTrue($this->container->has('test'));
    }

    /**
     * Tests ContainerInterface::get.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($class, new $class);

        $this->assertInstanceOf($class, $this->container->get($class));
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\ContainerExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithContainerException()
    {
        $this->setExpectedException('Psr\Container\ContainerExceptionInterface');

        $this->container->set('Test', array());

        $this->container->get('Test');
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->get($class);
    }

    /**
     * Tests ContainerInterface::set.
     *
     * @return void
     */
    public function testSetMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($class, new $class);

        $this->assertTrue($this->container->has($class));
    }
}

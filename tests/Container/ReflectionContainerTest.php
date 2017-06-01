<?php

namespace Rougin\Slytherin\Container;

/**
 * Reflection Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ReflectionContainerTest extends \PHPUnit_Framework_TestCase
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
        $delegate = new \Rougin\Slytherin\Container\Container;

        $this->container = new \Rougin\Slytherin\Container\ReflectionContainer($delegate);
    }

    /**
     * Tests ContainerInterface::get.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->assertInstanceOf($class, $this->container->get($class));
    }

    /**
     * Tests ContainerInterface::get with parameter.
     *
     * @return void
     */
    public function testGetMethodWithParameter()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

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

        $this->container->get('Rougin\Slytherin\Fixture\Classes\WithInterfaceParameter');
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $this->container->get('Test');
    }
}

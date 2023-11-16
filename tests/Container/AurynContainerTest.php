<?php

namespace Rougin\Slytherin\Container;

/**
 * Auryn Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends \LegacyPHPUnit\TestCase
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
    protected function doSetUp()
    {
        class_exists('Auryn\Injector') || $this->markTestSkipped('Auryn is not installed.');

        $this->container = new \Rougin\Slytherin\Container\AurynContainer(new \Auryn\Injector);
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
        $this->assertInstanceOf($class, $this->container->get($class));
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\ContainerExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithContainerException()
    {
        $this->expectException('Psr\Container\ContainerExceptionInterface');

        $this->container->get('Test');
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->expectException('Psr\Container\NotFoundExceptionInterface');

        $class = 'Rougin\Slytherin\Fixture\Classes\NonexistentClass';

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

        $this->assertInstanceOf($class, $this->container->get($class));
    }
}

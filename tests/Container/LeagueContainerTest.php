<?php

namespace Rougin\Slytherin\Container;

/**
 * League Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class LeagueContainerTest extends \LegacyPHPUnit\TestCase
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
        class_exists('League\Container\Container') || $this->markTestSkipped('League Container is not installed.');

        $this->container = new \Rougin\Slytherin\Container\LeagueContainer;
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
     * Tests ContainerInterface::get with Psr\Container\NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->expectException('Psr\Container\NotFoundExceptionInterface');

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

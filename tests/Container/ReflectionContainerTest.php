<?php

namespace Rougin\Slytherin\Container;

/**
 * Reflection Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainerTest extends \LegacyPHPUnit\TestCase
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
     * Tests ContainerInterface::get with multiple parameters.
     *
     * @return void
     */
    public function testGetMethodWithMultipleParameters()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\ParameterClass';

        $expected = 'With multiple parameters';

        $object = $this->container->get($class);

        $this->assertEquals($expected, $object->index());
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\ContainerExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithContainerException()
    {
        $this->expectException('Psr\Container\ContainerExceptionInterface');

        $this->container->get('Rougin\Slytherin\Fixture\Classes\WithInterfaceParameter');
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->expectException('Psr\Container\NotFoundExceptionInterface');

        $this->container->get('Test');
    }
}

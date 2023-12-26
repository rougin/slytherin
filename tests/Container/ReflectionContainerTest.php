<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Testcase;

/**
 * Reflection Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ReflectionContainerTest extends Testcase
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Sets up the container.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $delegate = new Container;

        $this->container = new ReflectionContainer($delegate);
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

        /** @var \Rougin\Slytherin\Fixture\Classes\ParameterClass */
        $object = $this->container->get($class);

        $this->assertEquals($expected, $object->index());
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

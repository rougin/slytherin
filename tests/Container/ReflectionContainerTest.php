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
        $expected = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $actual = $this->container->get($expected);

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Tests ContainerInterface::get with parameter.
     *
     * @return void
     */
    public function testGetMethodWithParameter()
    {
        $expected = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

        $actual = $this->container->get($expected);

        $this->assertInstanceOf($expected, $actual);
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

        $actual = $object->index();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentCl');
    }
}

<?php

namespace Rougin\Slytherin\Container;

/**
 * Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends \Rougin\Slytherin\Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * Sets up the container instance.
     *
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
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

        $this->container->alias('test', (string) $class);

        $this->assertTrue($this->container->has('test'));
    }

    /**
     * Tests ContainerInterface::get.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $expected = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($expected, new $expected);

        $result = $this->container->get((string) $expected);

        $this->assertInstanceOf($expected, $result);
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

        $this->container->get((string) 'Test');
    }

    /**
     * Tests ContainerInterface::get with NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentClass');
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

<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
    }

    /**
     * @return void
     */
    public function test_setting_alias_to_an_instance()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($class, new $class);

        $this->container->alias('test', (string) $class);

        $this->assertTrue($this->container->has('test'));
    }

    /**
     * @return void
     */
    public function test_getting_a_simple_class()
    {
        $expected = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($expected, new $expected);

        $actual = $this->container->get((string) $expected);

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\ContainerExceptionInterface.
     *
     * @return void
     */
    public function test_getting_instance_with_container_exception()
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
    public function test_getting_instance_with_not_found_exception()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentClass');
    }

    /**
     * Tests ContainerInterface::set.
     *
     * @return void
     */
    public function test_setting_instance()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($class, new $class);

        $this->assertTrue($this->container->has($class));
    }
}

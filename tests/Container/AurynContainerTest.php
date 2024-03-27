<?php

namespace Rougin\Slytherin\Container;

use Auryn\Injector;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\ContainerInterface
     */
    protected $container;

    /**
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('Auryn\Injector'))
        {
            $this->markTestSkipped('Auryn is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->container = new AurynContainer(new Injector);
    }

    /**
     * @return void
     */
    public function test_getting_a_simple_class()
    {
        $expected = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($expected, new $expected);

        $actual = $this->container->get($expected);

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_instance_with_container_exception()
    {
        $this->setExpectedException('Psr\Container\ContainerExceptionInterface');

        $this->container->get('Test');
    }

    /**
     * @return void
     */
    public function test_getting_instance_with_not_found_exception()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $class = 'Rougin\Slytherin\Fixture\Classes\NonexistentClass';

        $this->container->get($class);
    }

    /**
     * @return void
     */
    public function test_setting_instance()
    {
        $expected = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->set($expected, new $expected);

        $actual = $this->container->get($expected);

        $this->assertInstanceOf($expected, $actual);
    }
}

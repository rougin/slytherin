<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Container\ReflectionContainer;
use Rougin\Slytherin\Testcase;

/**
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
     * @return void
     */
    protected function doSetUp()
    {
        $delegate = new Container;

        $this->container = new ReflectionContainer($delegate);
    }

    /**
     * @return void
     */
    public function test_getting_a_simple_class()
    {
        $expected = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $actual = $this->container->get($expected);

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_a_class_with_parameter()
    {
        $expected = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

        $actual = $this->container->get($expected);

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_a_class_with_multiple_parameters()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\ParameterClass';

        $expected = 'With multiple parameters';

        /** @var \Rougin\Slytherin\Fixture\Classes\ParameterClass */
        $object = $this->container->get($class);

        $actual = $object->index();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_instance_with_not_found_exception()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentCl');
    }
}

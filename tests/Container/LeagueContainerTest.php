<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Container\LeagueContainer;
use Rougin\Slytherin\Testcase;

/**
 * League Container Test Class
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class LeagueContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\LeagueContainer
     */
    protected $container;

    /**
     * Sets up the container.
     *
     * @return void
     */
    protected function doSetUp()
    {
        if (! class_exists('League\Container\Container'))
        {
            $this->markTestSkipped('League Container is not installed.');
        }

        $this->container = new LeagueContainer;
    }

    /**
     * Tests ContainerInterface::get.
     *
     * @return void
     */
    public function testGetMethod()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $expected = (string) $class;

        // Added "$shared" to true in the unit test ----
        $this->container->set($class, new $class, true);
        // ---------------------------------------------

        $actual = $this->container->get($class);

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Tests ContainerInterface::get with Psr\Container\NotFoundExceptionInterface.
     *
     * @return void
     */
    public function testGetMethodWithNotFoundException()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

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

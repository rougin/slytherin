<?php

namespace Rougin\Slytherin\Container;

use Rougin\Slytherin\Container\LeagueContainer;
use Rougin\Slytherin\Testcase;

/**
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
     * @return void
     */
    protected function doSetUp()
    {
        // @codeCoverageIgnoreStart
        if (! class_exists('League\Container\Container'))
        {
            $this->markTestSkipped('League Container is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->container = new LeagueContainer;
    }

    /**
     * @return void
     */
    public function test_getting_a_simple_class()
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
     * @return void
     */
    public function test_getting_instance_with_not_found_exception()
    {
        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NewClass');
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

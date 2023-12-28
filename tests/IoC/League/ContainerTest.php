<?php declare(strict_types = 1);

namespace Rougin\Slytherin\IoC\League;

use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * NOTE: To be removed in v1.0.0.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\IoC\League\Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $class = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

    /**
     * @var \Rougin\Slytherin\Fixture\Classes\WithParameter
     */
    protected $instance;

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

        $this->container = new Container;

        $this->instance = new WithParameter(new NewClass, new AnotherClass);
    }

    /**
     * @return void
     */
    public function test_adding_a_simple_class()
    {
        $this->container->add($this->class, $this->instance);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * @return void
     */
    public function test_setting_a_simple_class()
    {
        $this->container->set($this->class, $this->instance);

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * @return void
     */
    public function test_getting_a_simple_class()
    {
        // Should only use methods found in ContainerInterface ---
        // $this->container->add($this->class)
        //     ->withArgument(new NewClass)
        //     ->withArgument(new AnotherClass);
        // -------------------------------------------------------

        $this->container->set($this->class, $this->instance);

        $expected = $this->instance;

        $actual = $this->container->get($this->class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_checking_class_exists()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->container->add($class);

        $this->assertTrue($this->container->has($class));
    }
}

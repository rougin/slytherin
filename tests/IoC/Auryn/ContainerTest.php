<?php declare(strict_types = 1);

namespace Rougin\Slytherin\IoC\Auryn;

use Auryn\Injector;
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
     * @var \Rougin\Slytherin\IoC\Auryn\Container
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
        if (! class_exists('Auryn\Injector'))
        {
            $this->markTestSkipped('Auryn is not installed.');
        }
        // @codeCoverageIgnoreEnd

        $this->container = new Container(new Injector);

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
    public function test_adding_a_class_with_multiple_parameters()
    {
        $this->container->add($this->class, array(':class' => new NewClass));

        $this->assertTrue($this->container->has($this->class));
    }

    /**
     * @return void
     */
    public function test_getting_a_simple_class()
    {
        $this->container->add($this->class, $this->instance);

        $expected = $this->instance;

        $actual = $this->container->get($this->class);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_getting_class_with_an_error()
    {
        $this->setExpectedException('Rougin\Slytherin\Container\Exception\NotFoundException');

        $this->container->get('Rougin\Slytherin\Fixture\Classes\NonexistentClass');
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

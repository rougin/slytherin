<?php

namespace Rougin\Slytherin\IoC\League;

use Rougin\Slytherin\Fixture\Classes\AnotherClass;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Fixture\Classes\WithParameter;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ContainerTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Fixture\Classes\WithParameter
     */
    protected $class;

    /**
     * @var string
     */
    protected $name = 'Rougin\Slytherin\Fixture\Classes\WithParameter';

    /**
     * @var \Rougin\Slytherin\IoC\League\Container
     */
    protected $self;

    /**
     * @return void
     */
    public function test_passed_if_class_added()
    {
        $this->self->add($this->name, $this->class);

        $this->assertTrue($this->self->has($this->name));
    }

    /**
     * @return void
     */
    public function test_passed_if_class_exists()
    {
        $class = 'Rougin\Slytherin\Fixture\Classes\NewClass';

        $this->self->add($class);

        $this->assertTrue($this->self->has($class));
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_found()
    {
        $this->self->set($this->name, $this->class);

        // Verify the retrieved instance matches ---
        $expect = $this->class;

        $actual = $this->self->get($this->name);

        $this->assertEquals($expect, $actual);
        // -----------------------------------------
    }

    /**
     * @return void
     */
    public function test_passed_if_instance_set()
    {
        $this->self->set($this->name, $this->class);

        $this->assertTrue($this->self->has($this->name));
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->checkIfLeagueExists();

        $this->self = new Container;

        $class = new WithParameter(new NewClass, new AnotherClass);

        $this->class = $class;
    }
}

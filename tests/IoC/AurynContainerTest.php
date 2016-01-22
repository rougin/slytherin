<?php

namespace Rougin\Slytherin\Test\IoC;

use Rougin\Slytherin\IoC\AurynContainer;
use Rougin\Slytherin\IoC\ContainerInterface;

use Auryn\Injector;
use PHPUnit_Framework_TestCase;
use Rougin\Slytherin\Test\Fixtures\TestClass;
use Rougin\Slytherin\Test\Fixtures\TestController;
use Rougin\Slytherin\Test\Fixtures\TestWithParameterController;

/**
 * Auryn Container Test
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class AurynContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\IoC\ContainerInterface
     */
    protected $container;

    /**
     * Sets up the container.
     *
     * @return void
     */
    public function setUp()
    {
        $this->container = new AurynContainer;
    }

    /**
     * Tests if the added instance exists.
     * 
     * @return void
     */
    public function testAddMethod()
    {
        $this->container->add(TestWithParameterController::class);

        $this->assertTrue($this->container->has(TestWithParameterController::class));
    }

    /**
     * Tests if the specified instance can be returned.
     * 
     * @return void
     */
    public function testGetMethod()
    {
        $this->container->add(TestWithParameterController::class);

        $this->assertEquals(
            new TestWithParameterController(new TestClass),
            $this->container->get(TestWithParameterController::class)
        );
    }

    /**
     * Tests if the added instance exists.
     * 
     * @return void
     */
    public function testHasMethod()
    {
        $this->container->add(TestController::class);

        $this->assertTrue($this->container->has(TestController::class));
    }

    /**
     * Tests if the container is implemented in ContainerInterface.
     * 
     * @return void
     */
    public function testContainerInterface()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->container);
    }
}

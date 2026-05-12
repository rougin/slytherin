<?php

namespace Rougin\Slytherin\Routing;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Fixture\Classes\NewClass;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Testcase;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class RoutingIntegrationTest extends Testcase
{
    /**
     * @var \Rougin\Slytherin\Container\Container
     */
    protected $container;

    /**
     * @return void
     */
    public function test_failed_if_router_not_found()
    {
        $expect = 'Rougin\Slytherin\Container\NotFoundException';

        $this->doSetExpectedException($expect);

        $config = new Configuration;

        $config->set('app.router', new NewClass);

        $self = new RoutingIntegration;

        $self->define($this->container, $config);
    }

    /**
     * @return void
     */
    public function test_passed_if_router_integrated()
    {
        $config = new Configuration;

        $config->set('app.router', new Router);

        $self = new RoutingIntegration;

        $container = $self->define($this->container, $config);

        $router = 'Rougin\Slytherin\Routing\RouterInterface';

        $this->assertTrue($container->has($router));
    }

    /**
     * @return void
     */
    protected function doSetUp()
    {
        $this->container = new Container;
    }
}

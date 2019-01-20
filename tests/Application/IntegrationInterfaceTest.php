<?php

namespace Rougin\Slytherin\Application;

/**
 * Integration Interface Test
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class IntegrationInterfaceTest extends ApplicationTestCases
{
    /**
     * Prepares the application instance.
     *
     * @return void
     */
    public function setUp()
    {
        $integrations = array();

        $integrations[] = 'Rougin\Slytherin\Debug\ErrorHandlerIntegration';
        $integrations[] = 'Rougin\Slytherin\Http\HttpIntegration';
        $integrations[] = 'Rougin\Slytherin\Routing\RoutingIntegration';

        $config = new \Rougin\Slytherin\Integration\Configuration;

        $config->set('app.router', $this->router());

        if (interface_exists('Interop\Http\ServerMiddleware\MiddlewareInterface')) {
            $integrations[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';

            $middlewares = array('Rougin\Slytherin\Fixture\Middlewares\EmptyMiddleware');

            $config->set('app.middlewares', $middlewares);
        }

        $app = new \Rougin\Slytherin\Application;

        $app->integrate('Rougin\Slytherin\Template\RendererIntegration');
        $app->integrate('Rougin\Slytherin\Integration\ConfigurationIntegration');

        $this->application = $app->integrate($integrations, $config);
    }

    /**
     * Tests the instances of static::$container.
     *
     * @return
     */
    public function testStaticContainer()
    {
        $container = \Rougin\Slytherin\Application::container();

        $interface = 'Rougin\Slytherin\Template\RendererInterface';

        $this->assertTrue($container->has($interface));
    }
}

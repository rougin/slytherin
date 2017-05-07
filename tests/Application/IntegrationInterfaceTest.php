<?php

namespace Rougin\Slytherin\Application;

/**
 * Integration Interface Test
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
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

        array_push($integrations, 'Rougin\Slytherin\Debug\ErrorHandlerIntegration');
        array_push($integrations, 'Rougin\Slytherin\Http\HttpIntegration');
        array_push($integrations, 'Rougin\Slytherin\Integration\ConfigurationIntegration');
        array_push($integrations, 'Rougin\Slytherin\Middleware\MiddlewareIntegration');
        array_push($integrations, 'Rougin\Slytherin\Routing\RoutingIntegration');
        array_push($integrations, 'Rougin\Slytherin\Template\RendererIntegration');

        $config = new \Rougin\Slytherin\Integration\Configuration;

        $config->set('app.router', $this->router());

        $middlewares = array('Rougin\Slytherin\Fixture\Middlewares\EmptyMiddleware');

        $config->set('app.middlewares', $middlewares);

        $app = new \Rougin\Slytherin\Application;

        $this->application = $app->integrate($integrations, $config);
    }
}

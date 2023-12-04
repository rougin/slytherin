<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Integration\Configuration;

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
    protected function doSetUp()
    {
        $integrations = array();

        $integrations[] = 'Rougin\Slytherin\Debug\ErrorHandlerIntegration';
        $integrations[] = 'Rougin\Slytherin\Http\HttpIntegration';
        $integrations[] = 'Rougin\Slytherin\Routing\RoutingIntegration';
        $integrations[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';
        $integrations[] = 'Rougin\Slytherin\Template\RendererIntegration';
        $integrations[] = 'Rougin\Slytherin\Integration\ConfigurationIntegration';

        $config = new Configuration;

        $router = $this->router();

        $config->set('app.router', $router);

        $app = new Application;

        $this->application = $app->integrate($integrations, $config);
    }
}

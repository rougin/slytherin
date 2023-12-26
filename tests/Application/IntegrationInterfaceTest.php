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
        $items = array();

        $items[] = 'Rougin\Slytherin\Debug\ErrorHandlerIntegration';
        $items[] = 'Rougin\Slytherin\Http\HttpIntegration';
        $items[] = 'Rougin\Slytherin\Routing\RoutingIntegration';
        $items[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';
        $items[] = 'Rougin\Slytherin\Template\RendererIntegration';
        $items[] = 'Rougin\Slytherin\Integration\ConfigurationIntegration';

        $config = new Configuration;

        $config->set('app.router', $this->router());

        $app = new Application;

        $this->application = $app->integrate($items, $config);
    }
}

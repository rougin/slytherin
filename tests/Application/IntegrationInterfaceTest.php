<?php

namespace Rougin\Slytherin\Application;

use Rougin\Slytherin\Integration\Configuration;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class IntegrationInterfaceTest extends ApplicationTestCases
{
    /**
     * @return void
     */
    protected function doSetUp()
    {
        $config = new Configuration;

        $config->set('app.environment', 'production');

        $config->set('app.router', $this->router());

        $app = new Application;

        $items = array('Rougin\Slytherin\Http\HttpIntegration');

        $items[] = 'Rougin\Slytherin\Debug\ErrorHandlerIntegration';

        $items[] = 'Rougin\Slytherin\Routing\RoutingIntegration';

        $items[] = 'Rougin\Slytherin\Integration\ConfigurationIntegration';

        $items[] = 'Rougin\Slytherin\Middleware\MiddlewareIntegration';

        $items[] = 'Rougin\Slytherin\Template\RendererIntegration';

        $this->system = $app->integrate($items, $config);
    }
}

<?php

namespace Rougin\Slytherin;

/**
 * Application Test Class
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Slytherin\Application
     */
    protected $application;

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

        $router = new Routing\Router;

        $router->get('/', 'Rougin\Slytherin\Fixture\Classes\NewClass@index');
        $router->get('/store', 'Rougin\Slytherin\Fixture\Classes\NewClass@store');
        $router->get('/response', 'Rougin\Slytherin\Fixture\Classes\WithResponseInterface@index');

        $config = new Integration\Configuration;

        $config->set('app.router', $router);

        $app = new Application;

        $this->application = $app->integrate($integrations, $config);
    }

    /**
     * Tests Application::run.
     *
     * @runInSeparateProcess
     *
     * @return void
     */
    public function testRunMethod()
    {
        $this->expectOutputString('Hello');

        $this->application->run();
    }

    /**
     * Tests Application::dispatch.
     *
     * @return void
     */
    public function testDispatchMethod()
    {
        $result = $this->application->dispatch('GET', '/store', true);

        $this->assertEquals('Store', $result);
    }
}

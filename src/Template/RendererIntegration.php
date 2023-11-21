<?php

namespace Rougin\Slytherin\Template;

use Rougin\Slytherin\Application;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Integration\IntegrationInterface;

/**
 * Renderer Integration
 *
 * An integration for template renderers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class RendererIntegration implements IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @param  \Rougin\Slytherin\Integration\Configuration    $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(ContainerInterface $container, Configuration $config)
    {
        /** @var string|string[] */
        $path = $config->get('app.views', '');

        $renderer = new Renderer($path);

        if (class_exists('Twig_Environment'))
        {
            $loader = new \Twig_Loader_Filesystem($path);

            $environment = new \Twig_Environment($loader);

            $renderer = new TwigRenderer($environment);
        }

        $container->set(Application::RENDERER, $renderer);

        return $container;
    }
}

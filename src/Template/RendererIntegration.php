<?php

namespace Rougin\Slytherin\Template;

use Rougin\Slytherin\Integration\Configuration;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Renderer Integration
 *
 * An integration for template renderers to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class RendererIntegration implements \Rougin\Slytherin\Integration\IntegrationInterface
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
        if (class_exists('Twig_Environment')) {
            $loader = new \Twig_Loader_Filesystem($config->get('app.views', ''));
            $twig   = new \Twig_Environment($loader);

            $renderer = new TwigRenderer($twig);
        } else {
            $renderer = new VanillaRenderer($config->get('app.views', ''));
        }

        $container->set('Rougin\Slytherin\Template\RendererInterface', $renderer);

        return $container;
    }
}

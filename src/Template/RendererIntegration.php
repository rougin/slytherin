<?php

namespace Rougin\Slytherin\Template;

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
     * @param  array                                          $config
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container, array $config = array())
    {
        $renderer = new VanillaRenderer($config['app']['views']);

        if (class_exists('Twig_Environment')) {
            $loader = new \Twig_Loader_Filesystem($config['app']['views']);
            $twig   = new \Twig_Environment($loader);

            $renderer = new TwigRenderer($twig);
        }

        $container->set('Rougin\Slytherin\Template\RendererInterface', $renderer);

        return $container;
    }
}

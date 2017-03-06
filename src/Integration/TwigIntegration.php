<?php

namespace Rougin\Slytherin\Integration;

/**
 * Twig Integration
 *
 * An integration for Twig to be included in Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class TwigIntegration implements IntegrationInterface
{
    /**
     * Defines the specified integration.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @return \Rougin\Slytherin\Container\ContainerInterface
     */
    public function define(\Rougin\Slytherin\Container\ContainerInterface $container)
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Filesystem);

        $container->set('Twig_Environment', $twig);

        return $container;
    }
}

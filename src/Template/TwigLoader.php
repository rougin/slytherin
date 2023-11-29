<?php

namespace Rougin\Slytherin\Template;

/**
 * Twig Loader
 *
 * Backward compatibility for the Twig package.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class TwigLoader
{
    /**
     * @return boolean
     */
    public function exists()
    {
        return class_exists('Twig_Environment') || class_exists('Twig\Environment');
    }

    /**
     * @param  string|string[] $path
     * @return \Rougin\Slytherin\Template\RendererInterface
     */
    public function load($path)
    {
        /** @var class-string */
        $loader = 'Twig_Loader_Filesystem';

        /** @var class-string */
        $environment = 'Twig_Environment';

        if (class_exists('Twig\Environment'))
        {
            $loader = 'Twig\Loader\FilesystemLoader';

            $environment = 'Twig\Environment';
        }

        $loader = new \ReflectionClass($loader);

        $loader = $loader->newInstance($path);

        $environment = new \ReflectionClass($environment);

        /**
         * @var \Twig\Environment
         */
        $environment = $environment->newInstance($loader);

        return new TwigRenderer($environment);
    }
}

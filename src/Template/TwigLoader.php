<?php

namespace Rougin\Slytherin\Template;

/**
 * A backward compatibility class for the Twig package.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class TwigLoader
{
    /**
     * Check if any version of Twig is installed.
     *
     * @return boolean
     */
    public function exists()
    {
        return class_exists('Twig_Environment') || class_exists('Twig\Environment');
    }

    /**
     * Loads the Twig instance.
     *
     * @param string|string[] $path
     *
     * @return \Twig\Environment
     */
    public function load($path)
    {
        /** @var class-string */
        $loader = 'Twig_Loader_Filesystem';

        /** @var class-string */
        $env = 'Twig_Environment';

        if (class_exists('Twig\Environment'))
        {
            /** @var class-string<\Twig\Loader\FilesystemLoader> */
            $loader = 'Twig\Loader\FilesystemLoader';

            /** @var class-string<\Twig\Environment> */
            $env = 'Twig\Environment';
        }

        $loader = new \ReflectionClass($loader);

        $loader = $loader->newInstance($path);

        $env = new \ReflectionClass($env);

        /** @var \Twig\Environment */
        return $env->newInstance($loader);
    }
}

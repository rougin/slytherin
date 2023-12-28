<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Template;

/**
 * Twig Loader
 *
 * A backward compatibility class for the Twig package.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
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
     * @param  string|string[] $path
     * @return \Twig\Environment
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

        /** @var \Twig\Environment */
        return $environment->newInstance($loader);
    }
}

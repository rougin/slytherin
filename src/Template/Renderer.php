<?php

namespace Rougin\Slytherin\Template;

use FilesystemIterator;
use InvalidArgumentException;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Renderer
 *
 * A simple implementation of a template renderer.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Renderer implements RendererInterface
{
    /**
     * @var array
     */
    protected $directories = [];

    /**
     * @param array $directories
     */
    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    /**
     * Renders a template.
     *
     * @param  string  $template
     * @param  array   $data
     * @return string
     */
    public function render($template, array $data = [])
    {
        $file = $this->getTemplate("$template.php");

        // Extracts the specific parameters to the template.
        extract($data);

        ob_start();
        
        include $file;

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }

    /**
     * Gets the specified template from the list of directories.
     *
     * @param  string $template
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function getTemplate($template)
    {
        foreach ($this->directories as $directory) {
            $location = new RecursiveDirectoryIterator(
                $directory,
                FilesystemIterator::SKIP_DOTS
            );

            $iterator = new RecursiveIteratorIterator(
                $location,
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $path) {
                if (strpos($path, $template) !== false) {
                    return $path;
                }
            }
        }

        throw new InvalidArgumentException('Template file not found.');
    }
}

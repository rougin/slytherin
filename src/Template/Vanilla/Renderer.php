<?php

namespace Rougin\Slytherin\Template\Vanilla;

use FilesystemIterator;
use InvalidArgumentException;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

use Rougin\Slytherin\Template\RendererInterface;

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
            if ($files = glob("$directory/$template")) {
                return $files[0];
            }
        }

        throw new InvalidArgumentException('Template file not found.');
    }
}

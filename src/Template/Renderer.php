<?php

namespace Rougin\Slytherin\Template;

use FilesystemIterator;
use InvalidArgumentException;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Rougin\Slytherin\Template\RendererInterface;

/**
 * Renderer
 *
 * A simple implementation of a renderer.
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
     * @param  boolean $toString
     * @return string
     */
    public function render($template, array $data = [], $toString = false)
    {
        $file = $this->getTemplate("$template.php");

        // Extracts the specific parameters to the template.
        if ($data) {
            extract($data);
        }

        // Buffers the file and get its output, else include the file
        if ($toString) {
            return $this->toString($file);
        }

        include $file;
    }

    /**
     * Gets the specified template from the list of directories.
     *
     * @param  string $template
     *
     * @throws \InvalidArgumentException
     *
     * @return string
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

        return new InvalidArgumentException('Template file not found.');
    }

    /**
     * Returns the output to string.
     * 
     * @param  string $file
     * @return string
     */
    private function toString($file)
    {
        ob_start();

        $fileContents = file_get_contents($file);
        $removedTags = str_replace('<?=', '<?php echo ', $fileContents);
        $result = preg_replace('/;*\s*\?>/', '; ?>', $removedTags);

        echo eval('?>' . $result);

        $result = ob_get_contents();

        ob_end_clean();

        return $result;
    }
}

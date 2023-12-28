<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Template\Renderer;

/**
 * Template Component
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class TemplateComponent extends AbstractComponent
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     *
     * @var string
     */
    protected $type = 'template';

    /**
     * Returns an instance from the named class.
     * It's used in supporting component types for Slytherin.
     *
     * @return mixed
     */
    public function get()
    {
        return new Renderer(array());
    }
}

<?php

namespace Rougin\Slytherin\Fixture\Components;

use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\System\Errors\TemplateNotFound;
use Rougin\Slytherin\Template\Renderer;
use Rougin\Slytherin\Template\RendererInterface;

/**
 * Template Component
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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

    /**
     * @param \Rougin\Slytherin\Component\Collection $collection
     *
     * @return void
     */
    public function register(Collection $collection)
    {
        $result = $this->get();

        if (! $result instanceof RendererInterface)
        {
            throw new TemplateNotFound($result);
        }

        $collection->setTemplate($result);
    }
}

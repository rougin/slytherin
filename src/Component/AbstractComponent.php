<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Component;

/**
 * Component Abstract
 *
 * Methods used for integrating a component to Slytherin.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
abstract class AbstractComponent implements ComponentInterface
{
    /**
     * Type of the component:
     * container, dispatcher, debugger, http, middleware, template
     *
     * @var string
     */
    protected $type = '';

    /**
     * Returns the type of the component.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

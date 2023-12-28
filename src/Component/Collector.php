<?php declare(strict_types = 1);

namespace Rougin\Slytherin\Component;

use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Container\ContainerInterface;

/**
 * Component Collector
 *
 * Collects all defined components into a Collection.
 *
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Collector
{
    /**
     * @var \Rougin\Slytherin\Component\ComponentInterface[]
     */
    protected $items = array();

    /**
     * @param \Rougin\Slytherin\Component\ComponentInterface[]|string[] $items
     */
    public function __construct(array $items = array())
    {
        foreach ($items as $item)
        {
            if (is_string($item))
            {
                /** @var \Rougin\Slytherin\Component\ComponentInterface */
                $item = new $item;
            }

            array_push($this->items, $item);
        }
    }

    /**
     * Creates a new collection.
     *
     * @param  \Rougin\Slytherin\Container\ContainerInterface $container
     * @return \Rougin\Slytherin\Component\Collection
     */
    public function make(ContainerInterface $container)
    {
        $collection = new Collection;

        $collection->setContainer($container);

        // If there is a defined container, set it first -----------------
        foreach ($this->items as $item)
        {
            if ($item->getType() === 'container')
            {
                /** @var \Rougin\Slytherin\Container\ContainerInterface */
                $result = $item->get();

                $collection->setDependencyInjector($result);
            }
        }
        // ---------------------------------------------------------------

        foreach ($this->items as $item)
        {
            if ($item->getType() === 'dispatcher')
            {
                /** @var \Rougin\Slytherin\Dispatching\DispatcherInterface */
                $result = $item->get();

                $collection->setDispatcher($result);
            }

            if (in_array($item->getType(), array('debugger', 'error_handler')))
            {
                /** @var \Rougin\Slytherin\Debug\DebuggerInterface */
                $result = $item->get();

                $collection->setErrorHandler($result);
            }

            if ($item->getType() === 'http')
            {
                /** @var array<int, mixed> */
                $result = $item->get();

                /** @var \Psr\Http\Message\ServerRequestInterface */
                $request = $result[0];

                /** @var \Psr\Http\Message\ResponseInterface */
                $response = $result[1];

                $collection->setHttp($request, $response);
            }

            if ($item->getType() === 'middleware')
            {
                /** @var \Rougin\Slytherin\Middleware\DispatcherInterface */
                $result = $item->get();

                $collection->setMiddleware($result);
            }

            if ($item->getType() === 'template')
            {
                /** @var \Rougin\Slytherin\Template\RendererInterface */
                $result = $item->get();

                $collection->setTemplate($result);
            }
        }

        return $collection;
    }

    /**
     * Initializes the specified components.
     *
     * @param  \Rougin\Slytherin\Component\ComponentInterface[]|string[] $components
     * @param  \Rougin\Slytherin\IoC\ContainerInterface                  $container
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(array $components, ContainerInterface $container = null)
    {
        $self = new Collector($components);

        if (! $container) $container = new Container;

        return $self->make($container);
    }
}

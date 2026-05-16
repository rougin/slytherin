<?php

namespace Rougin\Slytherin\Component;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Container\Container;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Container\NotFoundException;
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Middleware\DispatcherInterface as MiddlewareInterface;
use Rougin\Slytherin\Routing\DispatcherInterface as RoutingInterface;
use Rougin\Slytherin\System;
use Rougin\Slytherin\Template\RendererInterface;

/**
 * Collects all defined components into a Collection.
 *
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
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
                $item = new $item;
            }

            if (! $item instanceof ComponentInterface)
            {
                $error = System::componentNotFound($item);

                throw new NotFoundException($error);
            }

            $this->items[] = $item;
        }
    }

    /**
     * Creates a new collection.
     *
     * @param \Rougin\Slytherin\Container\ContainerInterface $container
     *
     * @return \Rougin\Slytherin\Component\Collection
     */
    public function make(ContainerInterface $container)
    {
        $collection = new Collection;

        $collection->setContainer($container);

        // If there is a defined container, set it first -----------------
        foreach ($this->items as $item)
        {
            if ($item->getType() !== 'container')
            {
                continue;
            }

            $result = $item->get();

            if (! $result instanceof ContainerInterface)
            {
                $error = System::containerNotFound($result);

                throw new NotFoundException($error);
            }

            $collection->setDependencyInjector($result);
        }
        // ---------------------------------------------------------------

        foreach ($this->items as $item)
        {
            if ($item->getType() === 'dispatcher')
            {
                $result = $item->get();

                if (! $result instanceof RoutingInterface)
                {
                    $error = System::dispatcherNotFound($result);

                    throw new NotFoundException($error);
                }

                $collection->setDispatcher($result);
            }

            if (in_array($item->getType(), array('debugger', 'error_handler')))
            {
                $result = $item->get();

                if (! $result instanceof ErrorHandlerInterface)
                {
                    $error = System::debuggerNotFound($result);

                    throw new NotFoundException($error);
                }

                $collection->setErrorHandler($result);
            }

            if ($item->getType() === 'http')
            {
                $result = $item->get();

                if (! is_array($result))
                {
                    throw new NotFoundException('Component is not an array');
                }

                $request = $result[0];

                if (! $request instanceof ServerRequestInterface)
                {
                    $error = System::requestNotFound($request);

                    throw new NotFoundException($error);
                }

                $response = $result[1];

                if (! $response instanceof ResponseInterface)
                {
                    $error = System::responseNotFound($response);

                    throw new NotFoundException($error);
                }

                $collection->setHttp($request, $response);
            }

            if ($item->getType() === 'middleware')
            {
                $result = $item->get();

                if (! $result instanceof MiddlewareInterface)
                {
                    $error = System::middlewareNotFound($result);

                    throw new NotFoundException($error);
                }

                $collection->setMiddleware($result);
            }

            if ($item->getType() === 'template')
            {
                $result = $item->get();

                if (! $result instanceof RendererInterface)
                {
                    $error = System::templateNotFound($result);

                    throw new NotFoundException($error);
                }

                $collection->setTemplate($result);
            }
        }

        return $collection;
    }

    /**
     * Initializes the specified components.
     *
     * @param \Rougin\Slytherin\Component\ComponentInterface[]|string[] $components
     * @param \Rougin\Slytherin\Container\ContainerInterface|null       $container
     *
     * @return \Rougin\Slytherin\Component\Collection
     */
    public static function get(array $components, $container = null)
    {
        $self = new Collector($components);

        $container = $container ? $container : new Container;

        return $self->make($container);
    }
}

<?php

namespace Rougin\Slytherin\Fixture\Components;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Component\AbstractComponent;
use Rougin\Slytherin\Component\Collection;
use Rougin\Slytherin\Container\ContainerInterface;
use Rougin\Slytherin\Debug\ErrorHandlerInterface;
use Rougin\Slytherin\Middleware\DispatcherInterface as MiddlewareInterface;
use Rougin\Slytherin\Routing\DispatcherInterface as RoutingInterface;
use Rougin\Slytherin\System\Errors\ComponentNotArray;
use Rougin\Slytherin\System\Errors\ContainerNotFound;
use Rougin\Slytherin\System\Errors\DebuggerNotFound;
use Rougin\Slytherin\System\Errors\DispatcherNotFound;
use Rougin\Slytherin\System\Errors\MiddlewareNotFound;
use Rougin\Slytherin\System\Errors\RequestNotFound;
use Rougin\Slytherin\System\Errors\ResponseNotFound;
use Rougin\Slytherin\System\Errors\TemplateNotFound;
use Rougin\Slytherin\Template\RendererInterface;

/**
 * @package Slytherin
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class SampleComponent extends AbstractComponent
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $type
     * @param mixed  $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @param \Rougin\Slytherin\Component\Collection $collection
     *
     * @return void
     */
    public function register(Collection $collection)
    {
        if ($this->type === 'container')
        {
            $result = $this->get();

            if (! $result instanceof ContainerInterface)
            {
                throw new ContainerNotFound($result);
            }

            $collection->setDependencyInjector($result);

            return;
        }

        if ($this->type === 'dispatcher')
        {
            $result = $this->get();

            if (! $result instanceof RoutingInterface)
            {
                throw new DispatcherNotFound($result);
            }

            $collection->setDispatcher($result);

            return;
        }

        if (in_array($this->type, array('debugger', 'error_handler')))
        {
            $result = $this->get();

            if (! $result instanceof ErrorHandlerInterface)
            {
                throw new DebuggerNotFound($result);
            }

            $collection->setErrorHandler($result);

            return;
        }

        if ($this->type === 'http')
        {
            $result = $this->get();

            if (! is_array($result))
            {
                throw new ComponentNotArray;
            }

            $request = $result[0];

            if (! $request instanceof ServerRequestInterface)
            {
                throw new RequestNotFound($request);
            }

            $response = $result[1];

            if (! $response instanceof ResponseInterface)
            {
                throw new ResponseNotFound($response);
            }

            $collection->setHttp($request, $response);

            return;
        }

        if ($this->type === 'middleware')
        {
            $result = $this->get();

            if (! $result instanceof MiddlewareInterface)
            {
                throw new MiddlewareNotFound($result);
            }

            $collection->setMiddleware($result);

            return;
        }

        if ($this->type === 'template')
        {
            $result = $this->get();

            if (! $result instanceof RendererInterface)
            {
                throw new TemplateNotFound($result);
            }

            $collection->setTemplate($result);

            return;
        }
    }
}

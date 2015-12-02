<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Interop\Container\ContainerInterface as Container;
use Rougin\Slytherin\Dispatching\DispatcherInterface as Dispatcher;
use Rougin\Slytherin\ErrorHandler\ErrorHandlerInterface as ErrorHandler;

/**
 * Component Collection
 *
 * Contains all the required components for Slytherin.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ComponentCollection
{
    /**
     * @var array
     */
    protected $components = [];

    /**
     * @param array $components
     */
    public function __construct(array $components = [])
    {
        $this->components = $components;
    }

    /**
     * Gets an instance of the container.
     * 
     * @return Container
     */
    public function getContainer()
    {
        return $this->getComponent('container');
    }

    /**
     * Sets the container.
     * 
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        return $this->setComponent('container', $container);
    }

    /**
     * Gets the dispatcher.
     * 
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        return $this->getComponent('dispatcher');
    }

    /**
     * Sets the dispatcher.
     * 
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        return $this->setComponent('dispatcher', $dispatcher);
    }

    /**
     * Gets the error handler.
     * 
     * @return ErrorHandler
     */
    public function getErrorHandler()
    {
        return $this->getComponent('error_handler');
    }

    /**
     * Sets the error handler.
     * 
     * @param  ErrorHandler $errorHandler
     */
    public function setErrorHandler(ErrorHandler $errorHandler)
    {
        return $this->setComponent('error_handler', $errorHandler);
    }

    /**
     * Gets the HTTP components.
     * 
     * @return mixed
     */
    public function getHttp()
    {
        return [
            $this->getComponent('request'),
            $this->getComponent('response')
        ];
    }

    /**
     * Sets the HTTP components.
     * 
     * @param  Request  $request
     * @param  Response $response
     * @return array
     */
    public function setHttp(Request $request, Response $response)
    {
        $this->setComponent('request', $request);

        return $this->setComponent('response', $response);
    }

    /**
     * Gets the selected component.
     * 
     * @param  string $type
     * @return mixed
     */
    protected function getComponent($type)
    {
        if (! isset($this->components[$type])) {
            return null;
        }

        return $this->components[$type];
    }

    /**
     * Sets the selected component.
     * 
     * @param string $type
     * @param mixed  $component
     */
    protected function setComponent($type, $component)
    {
        $this->components[$type] = $component;

        return $this;
    }
}

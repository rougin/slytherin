<?php

namespace Rougin\Slytherin;

use Rougin\Slytherin\Http\RequestInterface as Request;
use Rougin\Slytherin\Http\ResponseInterface as Response;
use Rougin\Slytherin\Dispatching\DispatcherInterface as Dispatcher;
use Rougin\Slytherin\ErrorHandler\ErrorHandlerInterface as ErrorHandler;
use Rougin\Slytherin\IoC\DependencyInjectorInterface as DependencyInjector;

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
    protected $components;

    /**
     * @param array $components
     */
    public function __construct(array $components = array())
    {
        $this->components = $components;
    }

    /**
     * Gets an instance of the dependency injector
     * 
     * @return DependencyInjector
     */
    public function getDependencyInjector()
    {
        return $this->getComponent('dependency_injector');
    }

    /**
     * Sets the dependency injector.
     * 
     * @param DependencyInjector $injector
     */
    public function setDependencyInjector(DependencyInjector $injector)
    {
        return $this->setComponent('dependency_injector', $injector);
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
     * Sets the dispatcher
     * 
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        return $this->setComponent('dispatcher', $dispatcher);
    }

    /**
     * Gets the error handler
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
     * Gets the HTTP components
     * 
     * @return Request|Response
     */
    public function getHttp($type = '')
    {
        return ($type)
            ? $this->getComponent($type)
            : [
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

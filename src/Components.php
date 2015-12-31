<?php

namespace Rougin\Slytherin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;
use Rougin\Slytherin\Dispatching\DispatcherInterface;
use Rougin\Slytherin\Debugger\DebuggerInterface;

/**
 * Component Collection
 *
 * Contains all the required components for Slytherin.
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Components
{
    /**
     * @var array
     */
    protected $components = [];

    /**
     * Gets an instance of the container.
     * 
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer()
    {
        return $this->getComponent('container');
    }

    /**
     * Sets the container.
     * 
     * @param \Interop\Container\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        return $this->setComponent('container', $container);
    }

    /**
     * Gets the dispatcher.
     * 
     * @return \Rougin\Slytherin\Dispatching\DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->getComponent('dispatcher');
    }

    /**
     * Sets the dispatcher.
     * 
     * @param \Rougin\Slytherin\Dispatching\DispatcherInterface $dispatcher
     */
    public function setDispatcher(DispatcherInterface $dispatcher)
    {
        return $this->setComponent('dispatcher', $dispatcher);
    }

    /**
     * Gets the debugger.
     * 
     * @return \Rougin\Slytherin\Debugger\DebuggerInterface
     */
    public function getDebugger()
    {
        return $this->getComponent('debugger');
    }

    /**
     * Sets the debugger.
     * 
     * @param  \Rougin\Slytherin\Debugger\DebuggerInterface $debugger
     */
    public function setDebugger(DebuggerInterface $debugger)
    {
        return $this->setComponent('debugger', $debugger);
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
     * @param  \Psr\Http\Message\RequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface $response
     * @return array
     */
    public function setHttp(RequestInterface $request, ResponseInterface $response)
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
    private function getComponent($type)
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
    private function setComponent($type, $component)
    {
        $this->components[$type] = $component;

        return $this;
    }
}
